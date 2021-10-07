<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use App\Repositories\Admin\Delivery\DeliveryRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;



class ImportCsvJobs implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $file;
    protected $delivery;

    public function __construct( $file, $delivery )
    {
        $this->file = $file;
        $this->delivery = $delivery;
    }


    /**
     * Execute the job.
     *
     * @param $file
     * @return void
     */
    public function handle()
    {
        $file = fopen($this->file, "r");
        $x = 0;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($x > 0 && !empty($column[0]))
            {
                $parsedData['merchant'] = getMerchantIdByMerchantCode($column[0]);
                $parsedData['recipient_name'] = $column[1];
                $parsedData['recipient_number'] = strlen($column[2]) == 10 ? '0'.$column[2] : $column[2];
                $parsedData['recipient_email'] = $column[3];
                $parsedData['recipient_address'] = $column[4];
                $parsedData['google_verified_address'] = $column[5];
                $parsedData['recipient_zone'] = getCourierZoneIdByZoneCode($column[6]);
                $parsedData['plan'] = getPlanIdByPlanCode($column[7]);
                $parsedData['plan_returned_id'] = getPlanIdByPlanCode($column[8]);
                $parsedData['merchant_order_id'] = $column[9];
                $parsedData['amount_to_be_collected'] = (double)preg_replace('/[^0-9.]+/', '', $column[10]);;
                $parsedData['hub_id'] = getHubIdByHubCode($column[11]);
//                    $getCord = getCoordinateByRecipientAddress($column[4], $column[5]);
//                    $parsedData['latitude'] = $getCord['lat'];
//                    $parsedData['longitude'] = $getCord['lng'];
                $parsedData['latitude'] = "0.00000000";
                $parsedData['longitude'] = "0.00000000";
                $parsedData['notes'] = null;
                $this->delivery->store($parsedData);
            }
            $x++;
        }
         fclose($this->file);
    }
}
