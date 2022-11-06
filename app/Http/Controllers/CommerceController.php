<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductStock;
use App\Repositories\CartRepository;
use App\Repositories\CreditRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class CommerceController extends Controller
{

    protected $productRepository;
    protected $cartRepository;
    protected $reviewRepository;
    protected $transactionRepository;
    protected $creditRepository;


    public function __construct(ProductRepository $productRepository, CartRepository $cartRepository,
                                ReviewRepository $reviewRepository, TransactionRepository $transactionRepository,
                                CreditRepository $creditRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->reviewRepository = $reviewRepository;
        $this->transactionRepository = $transactionRepository;
        $this->creditRepository = $creditRepository;
    }


    /**
     *  Return description and statics about product specified by {product_id}
     *
     *  @param  Request request
     *  @param  string  product_id
     *  @return Response
     */
    public function getProductInfo(Request $request, string $product_id)
    {
        DB::beginTransaction();

        $product = $this->productRepository->getProduct($product_id);
        if (!$product) {
            return response([], 404);
        }

        /* @Todo Move functionality to getStockInfo(). */
        if (env('APP_ENV') != 'production') {
            $stock = $this->productRepository->getStockLevel($product_id);
            $product->stock = $stock->quantity_available;
        }

        $product->averageRating = $this->productRepository->getAverageRating($product_id);
        $product->reviewCount = $this->productRepository->getReviewCount($product_id);

        DB::commit();

        return response($product, 200)
            ->header('Content-Type', 'application/json');
    }


    /**
     * Purchase single item
     *
     * @param  Request $request
     * @return Response
     */
    public function purchase(Request $request)
    {
        DB::beginTransaction();
        try {
            $quantity = (int)$request->quantity;
            $uid = Auth::id();
            $productID = $request->productID;

            if ($request->quantity < 1) {
                DB::rollback();
                return response(['error' => "Invalid quantity:" . (string)$request->quantity], 412);
            }

            /* @Todo: Timestamp Collision Check */
            $queryResult = $this->productRepository->purchaseProduct($quantity, $uid, $productID);
            DB::commit();
            return response(json_encode($queryResult[0]), 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response(['result' => false], 500);
        }
    }



    /**
     * Add single item to cart
     *
     * @param  Request $request
     * @return Response
     */
    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        try {
            $cartItem = $this->cartRepository->getItem(Auth::id(), $request->productID);
            if ($cartItem != null) {
                return response(['error' => 'Item is already in cart.'], 400);
            }

            $this->cartRepository->createItem(Auth::id(), $request->productID, $request->quantity);
            DB::commit();
            return response([], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response([], 404);
        }
    }



    /**
     * Return current user's cart items
     *
     * @param  Request $request
     * @return Response
     */
    public function getCart(Request $request)
    {
        $cart = $this->cartRepository->getAll(Auth::id());
        return response([
            'result' => true,
            'cart' => $cart,
        ], 200);
    }



    /**
     * Remove an item from user's cart
     *
     * @param  Request $request
     * @param  string  $product_id
     * @return Response
     */
    public function deleteCartItem(Request $request, $product_id)
    {
        try {
            $this->cartRepository->deleteItem(Auth::id(), $product_id);
            return response([
                'result' => true,
            ], 200);
        } catch (QueryException $e) {

            return response([
                'result' => false,
                'error' => $e->getMessage(),
            ], 404);
        }
    }



    /**
     * Purchase all items in user's cart
     *
     * @param  Request $request
     * @return Response
     */
    public function orderAllInCart(Request $request)
    {

        /* @Todo: Check $item->price matches current price in database.
         */

        DB::select('SET SESSION wait_timeout = 5;');

        DB::beginTransaction();

        $credits = $this->creditRepository->get(Auth::id());
        $deleteCartQuery = DB::table('cart_items');

        try {
            /* Acquire locks for each item */
            $itemExpense = 0;
            foreach ($request->items as $item) {
                $item = (object) $item;
                $itemExpense += ($item->quantity * $item->price);
                $stock = DB::table('product_stocks')
                    ->where('product_id', '=', $item->productID)
                    ->lockForUpdate()
                    ->first()
                    ->quantity_available;

                if ($item->quantity < 1) {
                    DB::rollback();
                    return response(['error' => "Invalid quantity:" . (string)$item->quantity], 412);
                }
            }

            $timestamp = Carbon::now('Asia/Seoul');
            /* @Todo: Timestamp Collision Check */

            DB::table('transactions')
                ->insert([
                    'uid' => Auth::id(),
                    'date' => $timestamp,
                    'credits_expended' => $itemExpense,
                ]);

            $transactionID = DB::table('transactions')
                ->where('uid', '=', Auth::id())
                ->where('date', '=', $timestamp)
                ->select('id')
                ->first()->id;

            foreach ($request->items as $key => $item) {
                $item = (object) $item;
                $stock = ProductStock::where('product_id', '=', $item->productID)
                    ->first()->quantity_available;

                /* Credit and Stock Check */
                if ($credits < $item->quantity * $item->price) {
                    DB::rollback();
                    return response([
                        'result' => false,
                        'error' => 'You do not have enough credits',
                    ], 400);
                }

                if ($item->quantity > $stock) {
                    DB::rollback();
                    return response([
                        'result' => false,
                        'error' => "Insufficient Stock for item '" . "{$item->title}'",
                    ], 400);
                }

                DB::table('product_stocks')
                    ->where('product_id', '=', $item->productID)
                    ->update(['quantity_available' => ($stock - $item->quantity)]);
                DB::table('product_purchases')
                    ->insert([
                        'uid' => Auth::id(),
                        'product_id' => $item->productID,
                        'quantity' => $item->quantity,
                        'transaction_id' => $transactionID,
                        'unit_price' => $item->price,
                        'authorized' => true,
                        'stock_after_transaction' => ($stock - $item->quantity),
                        'date' => $timestamp,
                    ]);

                $credits -= $item->quantity * $item->price;

                if ($key == 0) {
                    $deleteCartQuery = $deleteCartQuery->Where('product_id', '=', $item->productID);
                } else {
                    $deleteCartQuery = $deleteCartQuery->orWhere('product_id', '=', $item->productID);
                }

            }

            DB::table('credits')
                ->where('uid', '=', Auth::id())
                ->update(['credits' => $credits]);
            $deleteCartQuery->delete();

            DB::commit();

            return response([], 200);
        } catch (QueryException $e) {
            DB::rollback();
            return response(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Get past purchase records of current user
     *
     * @return Response
     */
    public function getPurchaseHistory()
    {
        $history = $this->transactionRepository->getHistory(Auth::id());
        return response($history, 200);
    }


    /**
     * Return user's credits amount
     *
     * @return Response
     */
    public function getCredits()
    {
        try{
            $credits = $this->creditRepository->get(Auth::id());
            return response(['credits' => $credits], 200);
        } catch (QueryException $e){
            return response([], 500);
        } catch (Exception $e) {
            return response([], 500);
        }
    }


    /**
     * Registers a new product review
     *
     * @param  Request $request
     * @return Response
     */
    public function submitReview(Request $request)
    {
        DB::beginTransaction();
        if (strlen($request->comment) >= 200) {
            return response([
                'error' => 'Maximum comment length is 200 characters!',
            ], 400);
        }

        if (empty($request->value) || $request->value > 5) {
            return response([
                'error' => 'please enter rate in range of 1~5 stars',
            ], 400);
        }

        $this->reviewRepository->create([
            "userID"        => Auth::id(),
            "purchaseID"    => $request->purchaseID,
            "value"         => $request->value,
            "comment"       => $request->comment,
        ]);

        DB::commit();
        return response([], 200);
    }


    public function getReview(Request $request)
    {
        $data = $this->reviewRepository->get($request->purchaseID);
        return response($data, 200);
    }
}
