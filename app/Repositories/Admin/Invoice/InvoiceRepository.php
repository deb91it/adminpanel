<?php namespace App\Repositories\Admin\Invoice;

interface InvoiceRepository
{
    public function getReportPaginated($request);
    public function getUnInvoiceReportPaginated($request);
    public function findInvoice($data);
    public function findPaidInvoice($data);
    public function findMerchantDeliveriesId($data);
    public function merchantDetails($merchant_id);
    public function saveDataMongo($merchant_id, $invoice_id);
}
