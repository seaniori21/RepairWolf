<?php

namespace App\Helpers\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

use App\Notifications\Channel\MessageChannel;
use App\Notifications\CustomerNotify;

use App\Models\Customer as CustomerModel;

use \Carbon\Carbon;

class Customer {
    protected static $notifyBaseData = [
        'via' =>  ['database','mail','sms'],
        'greeting' => 'Hi User',
        'subject' => 'Subject',
        'line' => 'The quick brown fox jumps over.',
    ];

    public static function Custom($data = [])
    {
        $response = null;
        $notifyData = array_merge(self::$notifyBaseData, $data);
        array_push($notifyData['via'], 'database');
        // $notifyData['created_at'] = Carbon::now();

        $key = array_search('sms', $notifyData['via']);

        if ($key !== false) {
            $notifyData['via'][$key] = MessageChannel::class;
        }

        // dd($notifyData);

        if (!empty($notifyData['to'])) {
            $customer = CustomerModel::where('id', $notifyData['to'])->first();

            if (!empty($customer)) {
                try {
                    $response = $customer->notify(new CustomerNotify($notifyData));
                } catch (\Exception $e) {
                    $response = $e;
                }
            }
        }

        return $response;
    }

    public static function scheduledSMS($order, $customer,  $notificationData, $productItem)
    {
        $notificationBodyData = $notificationData->data ? json_decode($notificationData->data) : [];

        $notifyData = [
            'via' =>  ['sms'],
            'to' => $customer->id,
            'greeting' => isset($notificationBodyData->greeting) && $notificationBodyData->greeting ? $notificationBodyData->greeting : '',
            'subject' => isset($notificationBodyData->subject) && $notificationBodyData->subject ? $notificationBodyData->subject : '',
            'line' => isset($notificationBodyData->line) && $notificationBodyData->line ? $notificationBodyData->line : '',
        ];

        $key = array_search('sms', $notifyData['via']);
        if ($key !== false) { $notifyData['via'][$key] = MessageChannel::class; }

        if (!empty($notifyData['to'])) {
            $customer = CustomerModel::where('id', $notifyData['to'])->first();

            if (!empty($customer)) {
                $customer->notify(new CustomerNotify($notifyData));
                $productItem->update(['notification_sent' => 1]);
            }
        }
    }
}