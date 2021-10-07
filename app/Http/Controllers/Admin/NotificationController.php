<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Hub;
use App\DB\Admin\Member;
use App\DB\Admin\PaymentInfo;
use App\DB\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Notification\NotificationRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\NotificationRequest;
use App\Http\Requests\Admin\NotificationEditRequest;
use Excel;
use DB;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $notification;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * NotificationController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        NotificationRepository $notification
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->notification = $notification;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
}
