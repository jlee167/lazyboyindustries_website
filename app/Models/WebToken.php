<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebToken extends Model
{
    public $last_update;

    protected $fillable = [
        'uid_protected',
        'uid_guardian',
        'token'
    ];

    protected $table = 'stream_webtokens';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
