<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTags extends Model
{

    protected $fillable = [
        'post_id',
        'tag_id',
        'name'
    ];

    protected $table = 'post_tags';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
