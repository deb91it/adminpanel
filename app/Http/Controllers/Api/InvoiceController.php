<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
//Added
use App\Http\Controllers\Controller;
use App\Repositories\Api\Invoice\InvoiceRepository;
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

    public function getInvoiceList(Request $request)
    {
        $deliveries = $this->invoice->getInvoiceList($request->get('per_page') == '' ? 50 : $request->get('per_page'));
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

    public function getInvoiceStatistics(Request $request)
    {
        $deliveries = $this->invoice->getInvoiceStatistics();
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any invoice !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any invoice!");
            return response($this->response, 200);
        }

//        $deliveries = $deliveries->toArray();
//        $deliveries['items'] = $deliveries['data'];
//        unset($deliveries['data']);
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
        if (!empty($invoice))
        {
            $rows = [];
            $rows['total_collected_amount'] = 0;
            $rows['total_receive_amount'] = 0;
            $rows['total_cod'] = 0;
            $rows['total_delivery_charge'] = 0;
            $rows['total_charge'] = 0;
            $rows['total_paid_out'] = 0;
            $rows['additional_amount'] = $invoice[0]->additional_amount;
            $rows['invoiced_amount'] = $invoice[0]->amount;
            $rows['invoiced_note'] = $invoice[0]->notes;

            foreach ($invoice as $val)
            {
                $rows['total_collected_amount'] += $val->amount_to_be_collected;
                $rows['total_receive_amount'] += $val->receive_amount;
                $rows['total_cod'] += $val->cod_charge;
                $rows['total_delivery_charge'] += $val->charge;
                $rows['total_charge'] = $rows['total_cod'] + $rows['total_delivery_charge'];
                $rows['total_paid_out'] = $rows['total_receive_amount'] -($rows['total_charge']);

            }

        }

        $this->response['success'] = true;
        $this->response['message']  = "Found deliver by the id {$data}";
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $invoice;
        $this->response['sum_data'] = $rows;
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
}
