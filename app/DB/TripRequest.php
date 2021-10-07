<?php

namespace App\DB;

use Illuminate\Database\Eloquent\Model;

class TripRequest extends Model
{
    protected $table = 'trip_requests';
    public $timestamps = false;
}
