<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $fillable = [
        'post_id',
        'uid'
    ];

    protected $table = 'post_likes';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

