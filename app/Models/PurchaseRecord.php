<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRecord extends Model
{
    public $id;

    protected $fillable = [
        'id ',
        'uid',
        'product_id',
        'quantity',
        'credits_expended',
        'comment',
        'authorized',
        'stock_after_transaction'
    ];

    protected $table = 'product_purchases';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
