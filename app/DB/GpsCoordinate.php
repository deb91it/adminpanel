<?php

namespace App\DB;

use Illuminate\Database\Eloquent\Model;

class GpsCoordinate extends Model
{
    protected $table = 'gps_coordinates';
    public $timestamps = false;
}
