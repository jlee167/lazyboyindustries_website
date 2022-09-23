<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    public $id;

    protected $fillable = [
        'uid',
        'contact',
        'type',
        'status',
        'contents'
    ];

    protected $table = 'support_request';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
