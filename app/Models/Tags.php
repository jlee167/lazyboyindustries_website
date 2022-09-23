<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{

    protected $fillable = [
        'name'
    ];

    protected $table = 'tags';
    public $primaryKey = 'id';
    public $timestamps = false;
}
