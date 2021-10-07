<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\InvoiceBulkMail;
use App\DB\Admin\Merchant;
use Illuminate\Http\Request;

use App\Http\Requests;
//Added
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Invoice\InvoiceRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class InvoiceController extends Controller
{
    protected $_errors;
    protected $_error_single_arr;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $invoice;

    function __construct(InvoiceRepository $invoice) {
        $this->invoice = $invoice;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index(Request $request)
    {
        $query_string = isset($request->query_string) ? $request->query_string : '';
        return view('admin.invoice.index',compact('query_string'));
    }

    public function getDataTableReport(Request $request){
        return $this->invoice->getReportPaginated($request);
    }

    public function unpaidInvoices(Request $request)
    {
        return view('admin.invoice.unpaid');
    }

    public function getUnpaidDataTableReport(Request $request){
        return $this->invoice->getUnInvoiceReportPaginated($request);
    }

    public function getInvoiceList(Request $request)
    {
        $deliveries = $this->invoice->getInvoiceList();
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any invoice !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any invoice!");
            return response($this->response, 200);
        }

        $deliveries = $deliveries->toArray();
        $deliveries['items'] = $deliveries['data'];
        unset($deliveries['data']);
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function getInvoiceByDate(Request $request, $data)
    {
        $invoice = $this->invoice->findInvoice($data);
        if (empty($invoice)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any invoice !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't find invoice by the id {$data}");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['message']  = "Found deliver by the id {$data}";
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $invoice;
        $this->response['error'] = null;
        return response($this->response, 200);
    }

    private function getErrorAsString() {
        $errorString ="";
        foreach ($this->_error_single_arr as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    public function viewInvoices(Request $request)
    {
        if ($request->invoice_type == "unpaid")
        {
            $invoice = $this->invoice->findInvoice($request);
        }else{
            $invoice = $this->invoice->findPaidInvoice($request);
        }
        if (!empty($invoice)){
            return response()->json(['success'=>true,'result'=>$invoice]);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Invoices Details not found.']);
    }

    public function invoiceNotes(Request $request)
    {
        $inv_notes = "";
        $customReq = [
            "invoice_date" => $request->invoice_date,
            "invoice_type" => $request->invoice_type,
            "merchant_id" => $request->merchant_id,
            "search_string" => $request->search_string,
            "date_range" => $request->date_range,
        ];
        $invoice_date = $request->invoice_date;
        if ($request->invoice_type == "unpaid")
        {
            $invoices = $this->invoice->findInvoice($customReq);
        }else{
            $invoices = $this->invoice->findPaidInvoice($customReq);
            $inv_notes = DB::table('invoices')->where(["id" => $_GET['invoice_id'],'invoice_date'=>$invoice_date, 'merchant_id' => $request->merchant_id,])->first();
        }
        if ($request->has('export'))
        {
            $arrays = [];
            $date = explode(' ~ ', $request['date_range']);
            $customReq['start_date'] = !empty($date[0]) ? $date[0] : '';
            $customReq['end_date'] = !empty($date[1]) ? $date[1] : '';
            foreach($invoices as $key =>  $object)
            {
                $arrays[$key]['consignment_id'] =  $object->consignment_id;
                $arrays[$key]['merchant_order_id'] =  $object->merchant_order_id;
                $arrays[$key]['status'] =  $object->flag_text;
                $arrays[$key]['amount_to_be_collected'] =  $object->amount_to_be_collected;
                $arrays[$key]['received_amount'] =  $object->receive_amount;
                $arrays[$key]['plan_charge'] =  $object->charge;
                $arrays[$key]['cod_charge'] =  $object->cod_charge;
                $arrays[$key]['recipient_name'] =  $object->recipient_name;
                $arrays[$key]['entry_date'] =  date('M j, Y', strtotime($object->created_at));
                $arrays[$key]['totals'] =  round($object->receive_amount - ($object->charge + $object->cod_charge));
            }
            $exp = postExportFile($customReq, $arrays, 'Export-invoice-', true);
            $file= public_path($exp['full']);
            $headers = array(
                'Content-Type: text/csv',
            );
            return Response::download($file, $exp['file'], $headers);
        }
        $merchant = $this->invoice->merchantDetails($request->merchant_id);
        return view('admin.invoice.delivery_invoice_details',compact('invoices','merchant','inv_notes','invoice_date'));
//        if (!empty($invoices)){
//            $merchant = $this->invoice->merchantDetails($request->merchant_id);
//        return view('admin.invoice.delivery_invoice_details',compact('invoices','merchant','inv_notes','invoice_date'));
//        }
//        return redirect('admin/invoice')->with('flashMessageError','Unable to show invoice list');
    }


    public function storeMultipleInvoiceNotes(Request $request)
    {
        if (empty($request->merchant_id))
        {
            return redirect('admin/unpaid-invoice')->with('flashMessageError','Please select at least one invoice');
        }

        foreach ($request->merchant_id as $key => $item)
        {

            $customArray = [
                "invoice_date" => (string) $request->invoices_date[$item],
                "merchant_id" => $item,
                "invoice_type" => "unpaid",
                "collected" => $request->collected[$item],
            ];
            $merchants[] = $this->invoice->merchantDetails($item);
            $invoices[] = $this->invoice->findInvoice($customArray);
            $inv_notes[] = DB::table('invoices')->where(['invoice_date'=>$request->invoices_date[$item], 'merchant_id' => $item])->first();
            $invoice_date[] = $request->invoices_date[$item];
            $storeInvoice = $this->invoice->findMerchantDeliveriesId($customArray);
            $invGen = $this->invoice->storeInvoiceNotes($storeInvoice);
            //$singleMerchant = $this->invoice->merchantDetails($item);
            //$saveMongo = $this->invoice->saveDataMongo($item, $invGen);

        }
        return view('admin.invoice.delivery_multiple_invoice_details',compact('invoices','merchants','inv_notes','invoice_date'))
            ->with("flashMessageSuccess","Merchant payment has been successfully done. Click on the print icon to print the invoices.");
    }

    public function storeInvoiceNotes(Request $request)
    {
        //print_r($request->all());exit();
        if (empty($request->delivery_id))
        {
            return redirect('admin/invoice-notes/'.$request->merchant_id.'?invoice_type=unpaid')->with('flashMessageError','Please select at least one consignment ID');
        }
        $invoices = $this->invoice->storeInvoiceNotes($request);
        if (!empty($invoices)){
            return redirect('admin/invoice')->with('flashMessageSuccess','Invoice note added successfully.');
        }
        return redirect('admin/invoice')->with('flashMessageError','Unable to add invoice note');
    }

    public function invDetailsMigration()
    {
        $db = DB::select(
          DB::raw("SELECT d.id as deli_id,i.id as inv_id,d.merchant_id as deli_merch_id, i.merchant_id as inv_merchant_id 
                FROM deliveries as d 
                join invoices as i 
                ON d.merchant_id = i.merchant_id 
                where d.invoice_date = i.invoice_date 
                order by d.id desc;")
        );
        if(!empty($db))
        {
            foreach ($db as $q)
            {
                $query = DB::table("invoice_details")->insert(
                    [
                        "invoice_id" => $q->inv_id,
                        "delivery_id" => $q->deli_id,
                        "created_at" => date("Y-m-d H:i:S"),
                    ]
                );
            }
        }
    }

}
