<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Simple check if API is online
Route::get('/arduino-signal-test', function () {
    return "API ROUTE IS WORKING";
});

// Main Arduino ingestion route
Route::post('/arduino-signal', [ArduinoController::class, 'store'])
    ->middleware('throttle:30,1');
