<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioSMSController extends Controller
{
  public static function sendAlertSMS($to, $messageText)
    {
        try {
            $sid   = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $from  = env('TWILIO_FROM');

            $client = new Client($sid, $token);

            $client->messages->create($to, [
                'from' => $from,
                'body' => $messageText
            ]);

            \Log::info("SMS sent to $to");

            return true;

        } catch (\Exception $e) {
            \Log::error("TWILIO ERROR: ".$e->getMessage());
            return false;
        }
    }
}