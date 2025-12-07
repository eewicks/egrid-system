<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertSettingsController;
use App\Http\Controllers\BackupRecoveryController;
use App\Http\Controllers\WebPushController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\TwilioSMSController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin-login', [AdminAuthController::class, 'postlogin'])
    ->name('admin.login.submit');

Route::post('/admin/logout', function(Request $request) {
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {
    Route::get('/admin/dashboard-test', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboardtest');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD API ROUTES  (IMPORTANT: KEEP WEB MIDDLEWARE!)
|--------------------------------------------------------------------------
| These MUST keep session + cookies for SB Admin JS.
| Removing middleware will break AJAX and cause empty cards.
|--------------------------------------------------------------------------
*/
// Route::prefix('admin/api')->group(function () {
//     Route::get('/stats', [AdminDashboardController::class, 'stats'])
//         ->name('api.stats');

//     Route::get('/logs', [AdminDashboardController::class, 'logs'])
//         ->name('api.logs');

//     Route::get('/device-status', [AdminDashboardController::class, 'deviceStatus'])
//         ->name('api.device-status');

//     Route::get('/devices', [AdminDashboardController::class, 'getDevices'])
//         ->name('api.devices');
// });

Route::middleware('web')->prefix('admin/api')->group(function () {
    Route::get('/devices', [AdminDashboardController::class, 'getDevices']);
    Route::get('/stats', [AdminDashboardController::class, 'stats']);
    Route::get('/logs', [AdminDashboardController::class, 'logs']);
});

/*
|--------------------------------------------------------------------------
| HEARTBEAT ENDPOINT (Arduino)
|--------------------------------------------------------------------------
*/
Route::post('/heartbeat', function (Request $request) {

    $device = \App\Models\Device::where('device_id', $request->device_id)->first();

    if (!$device) {
        return response()->json(['error' => 'Device not found'], 404);
    }

    // Heartbeat updates last_seen only
    $device->last_seen = now();
    $device->save();

    return response()->json(['success' => true]);
});

/*
|--------------------------------------------------------------------------
| CACHE FIX ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/fix-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('config:cache');
    return "CACHE CLEARED";
});


/*
|--------------------------------------------------------------------------
| TEST SMS ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/send-sms', function () {

    $apiKey = env('SEMAPHORE_API_KEY');
    $sender = env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE');
    $phone  = env('ALERT_PHONE');

    if (!$apiKey || !$phone) {
        return response()->json([
            "error" => "Missing environment variables",
            "api_key" => $apiKey,
            "phone" => $phone
        ]);
    }

    $response = Http::asForm()->post("https://api.semaphore.co/api/v4/messages", [
        "apikey"     => $apiKey,
        "number"     => $phone,
        "message"    => "EGMS Test SMS: Semaphore SMS is working!",
        "sendername" => $sender
    ]);

    return [
        "request_sent" => [
            "apikey" => $apiKey,
            "number" => $phone,
            "sender" => $sender
        ],
        "raw_response" => $response->body(),
        "json_response" => $response->json()
    ];
});

Route::get('/semaphore-test', [\App\Http\Controllers\SemaphoreSMSController::class, 'testSMS']);

Route::get('/test-sms', function () {

    $sid   = env('TWILIO_SID');
    $token = env('TWILIO_TOKEN');
    $from  = env('TWILIO_FROM');
    $to    = env('ALERT_PHONE');

    if (!$sid || !$token || !$from || !$to) {
        return "Missing environment variables!";
    }

    $response = Http::withBasicAuth($sid, $token)
        ->asForm()
        ->post("https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json", [
            "From" => $from,
            "To"   => $to,
            "Body" => "EGMS Test SMS: Your SMS integration is working!"
        ]);

    return $response->json();
});


/*
|--------------------------------------------------------------------------
| DEVICES CRUD
|--------------------------------------------------------------------------
*/
Route::resource('devices', DeviceController::class);


/*
|--------------------------------------------------------------------------
| DASHBOARDTEST VIEW
|--------------------------------------------------------------------------
*/
Route::get('/dashboardtest', fn() => view('dashboardtest'));


/*
|--------------------------------------------------------------------------
| ANALYTICS ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics.index');
Route::get('/analytics/outage-stats', [AnalyticsController::class, 'getOutageStats'])->name('analytics.outage-stats');
Route::get('/analytics/weekly-devices', [AnalyticsController::class, 'weeklyOutageAnalytics'])->name('analytics.weekly-devices');
Route::get('/analytics/weekly-outage-view', [AnalyticsController::class, 'getWeeklyOutageView'])->name('analytics.weekly-outage-view');
Route::get('/analytics/logs', [AnalyticsController::class, 'logs'])->name('analytics.logs');
Route::get('/analytics/weekly-outage-view-barangay', 
    [AnalyticsController::class, 'getWeeklyOutageViewBarangay']
)->name('analytics.weekly-outage-view-barangay');

/*
|--------------------------------------------------------------------------
| DASHBOARD POWER OUTAGE API
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
Route::get('/api/power-outages', [DashboardController::class, 'getPowerOutagesData']);


/*
|--------------------------------------------------------------------------
| ALERT SETTINGS ROUTES (FIXED)
|--------------------------------------------------------------------------
*/
Route::get('/settings/alerts', [AlertSettingsController::class, 'index'])
    ->name('settings.alerts');

Route::post('/settings/alerts/save', [AlertSettingsController::class, 'store'])
    ->name('settings.alerts.save');

Route::post('/settings/alerts/test', [AlertSettingsController::class, 'testAlert'])
    ->name('settings.alerts.test');


/*
|--------------------------------------------------------------------------
| BACKUP & RECOVERY
|--------------------------------------------------------------------------
*/
Route::get('/backup-recovery', [BackupRecoveryController::class, 'index'])
    ->name('backup_recovery.index');


/*
|--------------------------------------------------------------------------
| WEB PUSH ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('api/webpush')->group(function () {
    Route::get('/vapid-public-key', [WebPushController::class, 'getVapidPublicKey']);
    Route::post('/subscribe', [WebPushController::class, 'subscribe']);
    Route::post('/unsubscribe', [WebPushController::class, 'unsubscribe']);
    Route::post('/resubscribe', [WebPushController::class, 'resubscribe']);
    Route::post('/test', [WebPushController::class, 'testNotification']);
});


/*
|--------------------------------------------------------------------------
| SMS API ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('api/sms')->group(function () {
    Route::post('/test', [SMSController::class, 'testSMS']);
    Route::post('/outage-alert', [SMSController::class, 'sendOutageAlert']);
    Route::post('/simulate-outage', [SMSController::class, 'simulateOutage']);
    Route::get('/balance', [SMSController::class, 'getBalance']);
});
