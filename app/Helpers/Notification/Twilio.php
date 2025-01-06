<?php

namespace App\Helpers\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

use Illuminate\Support\Facades\Log;

class Twilio {
    public static function send($data)
    {
        $response = [
            'statusCode' => 200,
            'message' => '',
        ];

        try {
            $sid = env('TWILIO_ACCOUNT_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilio = new Client($sid, $token);

            $msgBody = '';

            if (isset($data['greeting'])) { $msgBody .= $data['greeting'].', '; }
            if (isset($data['line'])) { $msgBody .= $data['line']; }

            $message = $twilio->messages
                ->create($data['toNumber'], [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => $msgBody,
                ]);

            $response['message'] = 'SMS sent successfully. sid:'.$message->sid;

            Log::info('SMS sent successfully. sid:'.$message->sid);
        } catch (TwilioException $e) {
            // Handle Twilio exceptions here
            $response['statusCode'] = 404;
            $response['message'] = "Twilio Exception: " . $e->getMessage();

            Log::error("Twilio Exception: " . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other exceptions here
            $response['statusCode'] = 404;
            $response['message'] = "An unexpected error occurred: " . $e->getMessage();

            Log::error("An unexpected error occurred: " . $e->getMessage());
        }

        // dd($response);

        if ($response['statusCode'] != 200) {
            return response()->json(['error' => $response['message']], $response['statusCode']);
        } else {
            return response()->json(['message' => $response['message']], $response['statusCode']);
        }
    }
}


// <?php
//     // Update the path below to your autoload.php,
//     // see https://getcomposer.org/doc/01-basic-usage.md
//     require_once '/path/to/vendor/autoload.php';
//     use Twilio\Rest\Client;

//     $sid    = "AC801c7ce483f58823b9a3e4445c756645";
//     $token  = "95a66244382a9ebf495467ace120d7ad";
//     $twilio = new Client($sid, $token);

//     $message = $twilio->messages
//       ->create("+8801740389226", // to
//         array(
//           "from" => "+15165182177",
//           "body" => the quick brown fox
//         )
//       );

// print($message->sid);