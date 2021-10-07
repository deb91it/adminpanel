<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\FCMNotification\FCMNotificationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class FCMNotificationController extends Controller
{
    protected $notification;

    public function __construct(
        FCMNotificationRepository $notification
    )
    {
        $this->notification = $notification;
    }

    public function sendNotification(Request $request) {
        
    }
}
