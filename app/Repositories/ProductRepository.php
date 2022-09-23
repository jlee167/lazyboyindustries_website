<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductRating;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class ProductRepository
{
    public function __construct()
    {
    }


    /**
     * Get product info
     *
     * @param  int $productID
     * @return array
     */
    public function getProduct(int $productID)
    {
        return Product::where('id', '=', $productID)
                ->first();
    }


    /**
     * Get average rating of a product
     *
     * @param  int $productID
     * @return float
     */
    public function getAverageRating(int $productID) : float
    {
        return (float) DB::table('product_ratings')
            ->join('product_purchases', 'product_purchases.id', '=', 'product_ratings.purchase_id')
            ->where('product_purchases.product_id', '=', $productID)
            ->avg('product_ratings.value');
    }



    /**
     * Get number of reviews on a product
     *
     * @param  int $productID
     * @return int
     */
    public function getReviewCount(int $productID) : int
    {
        return (int) DB::table('product_ratings')
            ->join('product_purchases', 'product_purchases.id', '=', 'product_ratings.purchase_id')
            ->where('product_purchases.product_id', '=', $productID)
            ->count();
    }


    /**
     * Return stock level information for a product
     *
     * @param  int $productID
     * @return array
     */
    public function getStockLevel(int $productID)
    {
        return DB::table('product_stocks')
            ->where('id', '=', $productID)
            ->where('warehouse_id', '=', 1)
            ->first();
    }


    public function purchaseProduct($quantity, $uid, $productID)
    {
        DB::select("CALL PurchaseProduct(${quantity}, ${uid}, ${productID})");
    }

}
