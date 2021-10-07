<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class ImportCSV extends Model
{
   // protected $fillable = ['first_name' ,'last_name' ,'email','mobile','password', 'confirm_pass', 'user_type', 'user_role'];
    /**
     * Many-To-Many Relationship Method for accessing the user->roles
     *
     * @return QueryBuilder Object
     */

    protected $table = 'import_csv_logs';
    protected $primaryKey = 'id';

    public $timestamps = false;
}
