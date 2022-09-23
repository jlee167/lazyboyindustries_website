<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credits';

    protected $fillable = [
        'credits',
        'uid',
    ];

    protected $primaryKey = 'uid';
    public $timestamps = false;
}
