<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    public $id;

    protected $fillable = [
        'id ',
        'warehouse_id',
        'product_id',
        'quantity_available',
        'last_purchase',
        'disable_purchase'
    ];

    protected $table = 'product_stocks';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
