<?php

namespace App\DB\Agent;

//use Illuminate\Database\Eloquent\Model;
use Moloquent;

class AgentMongo extends Moloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'tests';


}
