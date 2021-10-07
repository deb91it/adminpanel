<?php

namespace App\Console\Commands;

use App\DB\Admin\ImportCSV;
use Illuminate\Console\Command;
use App\Repositories\Admin\Delivery\DeliveryRepository;
use Illuminate\Support\Facades\DB;

class ImportCsvFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:csv-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This function responsible for upload csv file in background process.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $delivery;
    public function __construct( DeliveryRepository $delivery)
    {
        parent::__construct();
        $this->delivery = $delivery;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csvJob = DB::table("import_csv_logs")->select("id","file_name")->where(["file_execute" => 1])->get();
        foreach ($csvJob as $job) {
            $filepath = public_path()."/resources/uploaded_admin_csv/".$job->file_name;
            if (file_exists($filepath)) {
                $up = ImportCSV::find($job->id);
                $up->file_execute = 0;
                $up->save();
                $file = fopen($filepath, "r");
                $x = 0;
                while (($column = fgetcsv($file, 100000, ",")) !== FALSE) {
                    if ($x > 0)
                    {
                        $parsedData['merchant'] = getMerchantIdByMerchantCode($column[0]);
                        $parsedData['recipient_name'] = $column[1];
                        $parsedData['recipient_number'] = $column[2];
                        $parsedData['recipient_email'] = $column[3];
                        $parsedData['recipient_address'] = $column[4];
                        $parsedData['google_verified_address'] = $column[5];
                        $parsedData['recipient_zone'] = getCourierZoneIdByZoneCode($column[6]);
                        $parsedData['plan'] = getPlanIdByPlanCode($column[7]);
                        $parsedData['amount_to_be_collected'] = $column[8];
                        $getCord = getCoordinateByRecipientAddress($column[4], $column[5]);
                        $parsedData['latitude'] = $getCord['lat'];
                        $parsedData['longitude'] = $getCord['lng'];
                        $parsedData['notes'] = null;
                        $this->delivery->store($parsedData);
                    }
                    $x++;
                }
                fclose($file);
                unlink($filepath);
                echo "File imported to database successfully ";
            }else{
                echo "File doesn't exist ";
            }

        }
    }
}
