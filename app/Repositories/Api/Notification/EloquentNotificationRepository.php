<?php namespace App\Repositories\Api\Notification;
use App\DB\Admin\Merchant;
use App\DB\Admin\Member;
use App\DB\Admin\PaymentDetails;
use App\DB\Admin\PlansAssign;
use App\DB\Permission;
use DB;
use Auth;
use Datatables;


class EloquentNotificationRepository implements NotificationRepository
{
    protected $notification;

    function __construct(Merchant $notification)
    {
        $this->notification = $notification;
    }

    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    public function merchantNotification($inputs, $merchant_id)
    {
        $resulArr = [];
        $date = date("Y-m-d");
        $fromConcad = " 00:00:01";
        $toConcad = " 23:59:59";
        $currentFrom = $date.$fromConcad;
        $currentTo = $date.$toConcad;
        $yesterday = date("Y-m-d", strtotime('-1 day')).$fromConcad;
        $last7Days = date("Y-m-d", strtotime('-7 days')).$fromConcad;

        $resulArr[0]['title'] = "today";
        $resulArr[0]['data'] = $this->getDataNotification($merchant_id, $currentFrom, $currentTo, $skip =0, $limit = 7);
        $resulArr[1]['title'] = "yesterday";
        $resulArr[1]['data'] = $this->getDataNotification($merchant_id, $yesterday, $currentTo, $skip =0, $limit = 3);
        $resulArr[2]['title'] = "last_seven_days";
        $resulArr[2]['data'] = $this->getDataNotification($merchant_id, $last7Days, $currentTo, $skip =0, $limit = 5);
        return $resulArr;
    }

    public function getDataNotification($merchant_id, $from, $to, $skip, $limit){
        $query = DB::table("tracking_details as td")
            ->select("td.id","d.consignment_id","td.deliveries_id",
                "td.flag_status_id","td.created_at","fs.flag_text","fs.color_code"
            )
            ->leftjoin("deliveries as d", "d.id","=","td.deliveries_id")
            ->leftjoin("flag_status as fs", "fs.id","=","td.flag_status_id")
            ->whereIn("td.flag_status_id", [6,7,8,9,10])
            ->where("d.merchant_id", $merchant_id)
            ->whereBetween("td.created_at",[$from, $to])
            ->orderBy("td.id", "DESC")
            ->skip($skip)
            ->take($limit)
            ->get();
        if (!empty($query))
        {
            foreach ($query as $q)
            {
                $q->notification_msg = "Consignment ID '{$q->consignment_id}' {$this->getFlagStatusMessage($q->flag_status_id)}";
            }
        }

        return $query;

    }
    private function getFlagStatusMessage($status)
    {
        switch ($status) {
            case 6:
                return "has been delivered.";
                break;
            case 7:
                return "has been returned from hub.";
                break;
            case 8:
                return "has been returned.";
                break;
            case 9:
                return "has been hold.";
                break;
            case 10:
                return "has been assigned to rider.";
                break;
            default:
                return "Invalid status.";
        }

    }

}
