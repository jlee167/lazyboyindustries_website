<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductRating;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class TransactionRepository
{
    public function __construct()
    {
    }

    public function getHistory(int $userID)
    {
        return DB::select("Call GetPurchaseHistory(${userID})");
    }
}
