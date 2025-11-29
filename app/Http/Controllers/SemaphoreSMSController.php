<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSService;

class SemaphoreSMSController extends Controller
{
     public function testSMS()
    {
        return [
            'api_key' => env('SEMAPHORE_API_KEY'),
            'sender'  => env('SEMAPHORE_SENDER_NAME'),
            'phone'   => env('ALERT_PHONE'),
        ];
    }
}
