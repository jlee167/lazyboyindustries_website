<?php

namespace App\Repositories\User;

use App\Models\Credit;
use Illuminate\Support\Facades\DB;



class CreditRepository
{

    public function __construct() {}

    public function get($userID)
    {
        return DB::table('credits')
            ->where('uid', '=', $userID)
            ->select('credits')
            ->first()->credits;
    }

    public function set($userID, $value)
    {
        $credits = new Credit([
            'uid' => $userID,
            'credits' => $value,
        ]);
        $credits->save();
    }
}
