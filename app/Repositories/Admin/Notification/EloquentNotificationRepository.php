<?php namespace App\Repositories\Admin\Notification;
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

}
