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
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin-login', [AdminAuthController::class, 'postlogin'])->name('admin.login.submit');

Route::post('/admin/logout', function(Request $request) {
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| API ROUTES (NO SESSION)
|--------------------------------------------------------------------------
*/
Route::get('/admin/api/stats', [AdminDashboardController::class, 'stats'])->withoutMiddleware('web');
Route::get('/admin/api/logs', [AdminDashboardController::class, 'logs'])->withoutMiddleware('web');
Route::get('/admin/api/device-status', [AdminDashboardController::class, 'deviceStatus'])->withoutMiddleware('web');
Route::get('/admin/api/devices', [AdminDashboardController::class, 'getDevices'])->withoutMiddleware('web');

/*
|--------------------------------------------------------------------------
| Heartbeat Route (Arduino)
|--------------------------------------------------------------------------
*/
Route::post('/heartbeat', function (Request $request) {
    $device = \App\Models\Device::where('device_id', $request->device_id)->first();

    if ($device) {
        $device->last_seen = now();
        $device->status = "ON";
        $device->save();
    }

    return response()->json(['success' => true]);
});

/*
|--------------------------------------------------------------------------
| TEST SMS ROUTE (TWILIO)
|--------------------------------------------------------------------------
*/
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
| Dashboard Test (Optional)
|--------------------------------------------------------------------------
*/
Route::get('/dashboardtest', fn() => view('dashboardtest'));

/*
|--------------------------------------------------------------------------
| Analytics Routes
|--------------------------------------------------------------------------
*/
Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics.index');
Route::get('/analytics/stats', [AnalyticsController::class, 'stats']);
Route::get('/analytics/logs', [AnalyticsController::class, 'logs']);
Route::get('/analytics/monthly-outages', [AnalyticsController::class, 'getMonthlyOutages']);
Route::get('/analytics/outage-stats', [AnalyticsController::class, 'getOutageStats']);
Route::get('/analytics/weekly-devices', [AnalyticsController::class, 'weeklyOutageAnalytics']);
Route::get('/analytics/weekly-outage-view', [AnalyticsController::class, 'getWeeklyOutageView']);

/*
|--------------------------------------------------------------------------
| Power Outages API
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
Route::get('/api/power-outages', [DashboardController::class, 'getPowerOutagesData']);

/*
|--------------------------------------------------------------------------
| Alert Settings
|--------------------------------------------------------------------------
*/
Route::get('/settings/alerts', [AlertSettingsController::class, 'index']);
Route::post('/settings/alerts/save', [AlertSettingsController::class, 'store']);
Route::post('/settings/alerts/test', [AlertSettingsController::class, 'testAlert']);

/*
|--------------------------------------------------------------------------
| Backup & Recovery
|--------------------------------------------------------------------------
*/
Route::get('/backup-recovery', [BackupRecoveryController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Push Notifications
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
| SMS API (Controller-Based)
|--------------------------------------------------------------------------
*/
Route::prefix('api/sms')->group(function () {
    Route::post('/test', [SMSController::class, 'testSMS']);
    Route::post('/outage-alert', [SMSController::class, 'sendOutageAlert']);
    Route::post('/simulate-outage', [SMSController::class, 'simulateOutage']);
    Route::get('/balance', [SMSController::class, 'getBalance']);
});
