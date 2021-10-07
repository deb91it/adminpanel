<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ImportFilesRequest;

use App\Http\Controllers\Controller;
use App\Jobs\ImportCSVJob;
use App\Jobs\ImportCsvJobs;
use App\Repositories\Admin\Delivery\DeliveryRepository;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;

class ImportFilesController extends Controller
{
    /**
     * @var DeliveryRepository
     */
    protected $delivery;

    function __construct(
        DeliveryRepository $delivery
    )
    {
        $this->delivery = $delivery;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function index() {
        //echo phpinfo();;exit();
        return view('admin.import-files.index');
    }

    public function parsingFiles(ImportFilesRequest $request) {
        $result = 'something went wrong.';
        $importFile = $request->hasFile('import_files');
        if ($importFile > 0) {
            $files = $request->file('import_files');
            $linecount = count(file($files));
            if ($linecount <= 1500) {
                if ($request->upload_type == 2) {
                    $result = $this->delayParsing($files);
                }else{
                    $result = $this->instantParsing($files);
                }
            }else{
                $result = $this->delayParsing($files);
            }
        }
        return redirect()->route('admin.import.files')->with('flashMessageSuccess',$result);
    }

    private function instantParsing($files)
    {
        if (strtolower($files->getClientOriginalExtension()) != 'csv')
        {
            return redirect()->route('admin.import.files')->with('flashMessageError','Invalid file type. only .csv file supported. ');
        }
        if ($files->getSize() > 0) {

            $file = fopen($files, "r");
            $x = 0;

            while (($column = fgetcsv($file, 100000, ",")) !== FALSE) {
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
            fclose($file);
        }

        return 'Data imported successfully';
    }

    private function delayParsing($files) {
        if ($files) {
            $save_path = public_path('resources/uploaded_admin_csv/');
            $csv_name = time().'.'.$files->getClientOriginalExtension();
            $files->move($save_path, $csv_name);
            $csv = DB::table("import_csv_logs")->insert([
                    "file_name" => $csv_name,
                    "mime_type" => $files->getClientOriginalExtension(),
                    "file_execute" => 1,
                    "uploaded_at" => date("Y-m-d H:i:s")
                ]
            );
            return 'File uploaded successfully. Data will import to database within a hour.';
        }
    }

//    private function getMerchantIdByMerchantCode($code) {
//        $query = DB::table("merchants")
//            ->select("id")
//            ->where(["merchant_code" => $code])
//            ->first();
//        if (empty($query))
//        {
//            return 0;
//        }
//        return $query->id;
//    }
//
//    private function getPlanIdByPlanCode($code) {
//        $query = DB::table("plans")
//            ->select("id")
//            ->where(["plan_code" => $code])
//            ->first();
//        if (empty($query))
//        {
//            return 0;
//        }
//        return $query->id;
//    }
//
//    private function getCourierZoneIdByZoneCode($code) {
//        $query = DB::table("courier_zones")
//            ->select("id")
//            ->where(["zone_code" => $code])
//            ->first();
//        if (empty($query))
//        {
//            return 0;
//        }
//        return $query->id;
//    }
//
//    private function getCoordinateByRecipientAddress($address, $google_map_address) {
//        $result = $this->callGoogleMapApis($google_map_address);
//        if (empty($result)) {
//            $result = $this->callGoogleMapApis($address);
//            if (empty($result)) {
//                if (strpos($address, ",")) {
//                    echo "<pre>";
//                    print_r(end(explode(",",$address)));
//                    echo "</pre>";
//                    exit();
//                    $result = $this->callGoogleMapApis(end(explode(",",$address)));
//                }
//                if (empty($result)) {
//                    $cor = ['lat' => '0.00000000', 'lng' => '0.00000000'];
//                    return $cor;
//                }
//            }
//
//        }
//        return $result;
//    }
//
//    private function callGoogleMapApis($address) {
//        $cor = [];
//        $client = new Client(); //GuzzleHttp\Client
//        $result =(string) $client->post("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyC4I-UdSRHCSIMrLQlKE68krPHAQVXpzy4")->getBody();
//        $json =json_decode($result);
//        if (empty($json->results))
//        {
//            return '';
//        }
//        $cor['lat'] = $json->results[0]->geometry->location->lat;
//        $cor['lng'] = $json->results[0]->geometry->location->lng;
//        return $cor;
//    }




}
