<?php

namespace App\Repositories\ECommerce;

use App\Models\Product;
use App\Models\ProductRating;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class ReviewRepository
{
    public function __construct(){}


    public function create(array $review)
    {
        $productRating = new ProductRating();
        $productRating->uid = $review["userID"];
        $productRating->purchase_id = $review["purchaseID"];
        $productRating->value = $review["value"];
        $productRating->comment = $review["comment"];
        $productRating->save();
    }


    public function get(int $purchaseID)
    {
        return ProductRating::where('purchase_id', '=', $purchaseID)
            ->first();
    }
}
