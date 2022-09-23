<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    public $id;
    public $uid;
    public $date_report;
    public $status;
    public $response;
    public $responders;
    public $stream_key;
    public $description;

    protected $table = 'streams';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
