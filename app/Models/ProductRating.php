<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $fillable = [
        'id ',
        'uid',
        'purchase_id',
        'value',
        'comment',
        'date'
    ];

    protected $table = 'product_ratings';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
