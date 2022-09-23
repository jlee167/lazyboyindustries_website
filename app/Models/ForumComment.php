<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    public $id;
    public $date;

    protected $fillable = [
        'author',
        'contents',
        'post_id',
        'parent_id',
        'depth'
    ];

    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
