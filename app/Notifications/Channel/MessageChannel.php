<?php
 
namespace App\Notifications\Channel;
 
use Illuminate\Notifications\Notification;
use App\Helpers\Notification\Twilio;

class MessageChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {        
        $message = $notification->toMessage($notifiable);

        $messageData = [
            'toNumber' => $notifiable->mobile,
            'subject' => $message['subject'],
            'greeting' => $message['greeting'],
            'line' => $message['line'],
        ];

        // dd($message);

        try {
            $response = Twilio::send($messageData);
        } catch (Exception $e) {
            $response = $e;
        }

        // dd($response->getData()->error);

        if ($response->status() != 200 && isset($message['notificationFromBackend']) && $message['notificationFromBackend'] === true) {
            throw new \Exception($response->getData()->error);            
            // return response()->json(['error' => $response->getData()->error], $response->status());
        } else {
            return response()->json(['message' => $response->getData()->message], $response->status());
        }
    }
}