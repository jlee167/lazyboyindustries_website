<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $id;

    protected $fillable = [
        'id ',
        'title',
        'description',
        'price_credits',
        'active'
    ];

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
