<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSService;

class SemaphoreSMSController extends Controller
{
     public function testSMS()
    {
        $to = env('ALERT_PHONE');
        $msg = "EGMS Test: Your SMS system is working using Semaphore.";

        $result = SMSService::send($to, $msg);

        return response()->json($result);
    }
}
