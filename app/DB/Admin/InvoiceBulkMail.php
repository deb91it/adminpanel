<?php

namespace App\DB\Admin;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class InvoiceBulkMail extends Eloquent {

    protected $connection = 'mongodb';

    protected $collection = 'bulk_email_merchant_invoice';

}
