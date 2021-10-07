<?php namespace App\Repositories\Admin\FCMNotification;

use App\DB\Admin\Expense;
use Illuminate\Support\Facades\DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class EloquentFCMNotificationRepository implements FCMNotificationRepository
{
    public function sendFCMNotification($inputs)
    {
        // TODO: Implement sendFCMNotification() method.
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder();
//        $notificationBuilder->setBody($inputs['contents'])
//            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            "title" => $inputs['title'],
            "item_contents" => $inputs['contents'],
            "type" => $inputs['type'],
            "item_id" => $inputs['item_id'],
            "consignment_id" => $inputs['consignment_id'],
//            "icon" => $inputs['icon'],
//            "click_icon" => $inputs['click_icon'],
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        
        $data = $dataBuilder->build();

// You must change it to get your tokens
        $tokens = $inputs['tokens'];

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

// return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

// return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

// return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

// return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
        $downstreamResponse->tokensWithError();
    }
}
