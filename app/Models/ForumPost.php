<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    public $date;

    protected $fillable = [
        'title',
        'forum',
        'author',
        'view_count',
        'contents'
    ];


    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
