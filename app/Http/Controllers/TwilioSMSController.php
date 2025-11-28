<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioSMSController extends Controller
{
     public function sendAlertSMS($deviceId, $messageText)
    {
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_NUMBER');
        $to     = env('ALERT_PHONE_NUMBER'); // Philippine number

        try {
            $client = new Client($sid, $token);

            $client->messages->create($to, [
                'from' => $from,
                'body' => $messageText
            ]);

            return response()->json(['success' => true, 'message' => 'SMS Sent']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testSMS()
    {
        return $this->sendAlertSMS('TEST_DEVICE', 'Twilio Test SMS: Your system is working!');
    }


}
