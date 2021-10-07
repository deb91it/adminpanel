<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to merchant users';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mail = [];
        date_default_timezone_set('Asia/Dhaka');
        $from = date("Y-m-d")." 00:00:01";
        $to = date("Y-m-d")." 23:59:59";

        $check = DB::table('invoices')->where('email_send_status', 0)->whereBetween('created_at', [$from, $to])->get();
        if (empty($check))
        {
            return $this->info('no invoices left to send email');
        }
        foreach ( $check as $m_key => $val )
        {

            $del[] = DB::table('invoice_details as iv')
                ->select('del.*')
                ->join('deliveries as del','del.id','=','iv.delivery_id')
                ->where('iv.invoice_id',$val->id)
                ->get();

            $singleMerchant = DB::table('merchants as m')
            ->select(
                "m.*",
                "mem.mobile_no",
                "mem.email as primary_mail")
            ->join("members as mem","mem.id","=","m.member_id")
            ->where("m.id", $val->merchant_id)
            ->first();

            $mail['to'] = $singleMerchant->primary_mail;
            $mail['subject'] = 'Provati - Merchant Invoice: '.$val->invoice_no;
            $mail['from'] = 'info@provati.com';

            if (send_mail($mail, $singleMerchant, $m_key, $del, $check) > 0)
            {
                DB::table('invoices')
                    ->where('id', $val->id)
                    ->update(['email_send_status' => 1]);
            }else{
                DB::table('email_logs')->insert(
                    [
                        'merchant_id' => $val->merchant_id,
                        'invoice_id' => $val->id,
                        'reason' => "couldn't send the mail.",
                        'created_at' => date('Y-m-d H:i:s'),

                    ]
                );
            }
        }
            $this->info('Process is completed successfully !');
        }
}
