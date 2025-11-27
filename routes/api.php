<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoController;
use App\Http\Controllers\ArduinoIngestController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
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
    ->middleware('throttle:30,1');   // limit 30 requests per minute