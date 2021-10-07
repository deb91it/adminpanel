<?php namespace App\Repositories\Api\Notification;

interface NotificationRepository
{
    public function merchantNotification($inputs, $merchant_id);
}
