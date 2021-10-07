<?php namespace App\Repositories\Admin\FCMNotification;

interface FCMNotificationRepository
{
    public function sendFCMNotification($inputs);
}
