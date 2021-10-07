<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class SqlController extends Controller
{
    private function getTransactionLogSql()
    {
        return "
                SELECT 
                  MAX(baal.id) AS max_trip_id,
                  @date := checkout_date,
                  checkout_date as c_date
                  @merchantId := merchant_id
                  merchant_id,
                  SUM(total_rent),
                  COUNT(id) AS total_trip,
                    (SELECT 
                            COUNT(id)
                        FROM
                            vehicles
                        WHERE
                            created_at BETWEEN CONCAT(@date, ' 00:00:00') AND CONCAT(@date, ' 23:59:59')
                                AND (merchant_id = @merchantId)) AS no_of_vehicle
                FROM
                    (SELECT 
                        t.id,
                            t.driver_id,
                            DATE_FORMAT(t.checkout_at, '%Y-%m-%d') AS checkout_date,
                            (SELECT 
                                    merchant_id
                                FROM
                                    drivers
                                WHERE
                                    id = driver_id) AS merchant_id,
                            t.total_rent,
                            t.payable_total,
                            t.ezzyr_commission
                    FROM
                        trips AS t) AS vt
                GROUP BY date , merchant_id;
                    ";
    }
}