<?php

namespace App\Repositories\ECommerce;

use App\Repositories\Base\SingleModelRepository;
use Illuminate\Support\Facades\DB;



class CartRepository extends SingleModelRepository
{

    public function __construct() {}



    /**
     * Get all items in user's cart
     *
     * @param  int $userID
     * @return array
     */
    public function getAll(int $userID)
    {
        return DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('uid', '=', $userID)
                ->get();
    }



    /**
     * Get a specific item in user's cart
     *
     * @param  int  $userID
     * @param  int  $productID
     * @return array
     */
    public function getItem(int $userID, int $productID)
    {
        return DB::table('cart_items')
                ->where('uid', '=', $userID)
                ->where('product_id', '=', $productID)
                ->first();
    }



    /**
     * Add an item in user's cart
     *
     * @param  int $userID
     * @param  int $productID
     * @param  int $quantity
     * @return array
     */
    public function createItem(int $userID, int $productID, int $quantity)
    {
        DB::table('cart_items')
            ->insert([
                'uid' => $userID,
                'product_id' => $productID,
                'quantity' => $quantity,
            ]);
    }



    /**
     * Delete an item in user's cart
     *
     * @param  int $userID
     * @param  int $productID
     * @return array
     */
    public function deleteItem(int $userID, int $productID)
    {
        DB::table('cart_items')
                ->where('uid', '=', $userID)
                ->where('product_id', '=', $productID)
                ->delete();
    }
}
