<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertSettingsController;
use App\Http\Controllers\BackupRecoveryController;
use App\Http\Controllers\WebPushController;
use App\Http\Controllers\SMSController;


/*
|--------------------------------------------------------------------------
| PUBLIC PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin-login', [AdminAuthController::class, 'postlogin'])
    ->name('admin.login.submit');


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD (Protected)
|--------------------------------------------------------------------------
|
| We use session('admin_logged_in') instead of Laravel Auth middleware.
| All dashboard API routes must live inside this SAME group.
|
*/

Route::middleware([])->group(function () {

    // MAIN DASHBOARD
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // DASHBOARD METRICS — Total Devices, ON/OFF, Outages
    Route::get('/admin/api/stats', [AdminDashboardController::class, 'stats'])
        ->name('admin.api.stats');

    // DASHBOARD LOGS — Latest 20 events table
    Route::get('/admin/api/logs', [AdminDashboardController::class, 'logs'])
        ->name('admin.api.logs');

    // DEVICE STATUS (derived ON/OFF with freshness)
    Route::get('/admin/api/device-status', [AdminDashboardController::class, 'deviceStatus'])
        ->name('admin.api.device_status');

    // DEVICES LIST with display status
    Route::get('/admin/api/devices', [AdminDashboardController::class, 'getDevices'])
        ->name('admin.api.devices');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/admin/logout', function(Request $request){
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');

Route::post('/logout', function(Request $request){
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


/*
|--------------------------------------------------------------------------
| DEVICE MANAGER (Resource CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('devices', DeviceController::class);


/*
|--------------------------------------------------------------------------
| TEST DASHBOARD VIEW (Optional)
|--------------------------------------------------------------------------
*/

Route::get('/dashboardtest', function() {
    return view('dashboardtest');
});


/*
|--------------------------------------------------------------------------
| ANALYTICS ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/analytics', [AnalyticsController::class, 'analytics'])
    ->name('analytics.index');

Route::get('/analytics/stats', [AnalyticsController::class, 'stats'])
    ->name('analytics.stats');

Route::get('/analytics/logs',  [AnalyticsController::class, 'logs'])
    ->name('analytics.logs');

Route::get('/analytics/monthly-outages', [AnalyticsController::class, 'getMonthlyOutages'])
    ->name('analytics.monthly-outages');

Route::get('/analytics/outage-stats', [AnalyticsController::class, 'getOutageStats'])
    ->name('analytics.outage-stats');

Route::get('/analytics/weekly-devices', [AnalyticsController::class, 'weeklyOutageAnalytics'])
    ->name('analytics.weekly-devices');

Route::get('/analytics/weekly-outage-view', [AnalyticsController::class, 'getWeeklyOutageView'])
    ->name('analytics.weekly-outage-view');


/*
|--------------------------------------------------------------------------
| DASHBOARD DATA (Charts)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])
    ->name('dashboard.stats');

Route::get('/api/power-outages', [DashboardController::class, 'getPowerOutagesData'])
    ->name('api.power-outages');


/*
|--------------------------------------------------------------------------
| ALERT SETTINGS
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
| WEB PUSH NOTIFICATIONS API
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
| ALERT LOGS API
|--------------------------------------------------------------------------
*/

Route::get('/api/alert-logs', function() {
    if (!session('admin_logged_in')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $logs = \App\Models\AlertLog::latest()->take(10)->get();
    
    return response()->json([
        'logs' => $logs->map(function($log) {
            return [
                'id' => $log->id,
                'device_id' => $log->device_id,
                'barangay' => $log->barangay,
                'alert_type' => $log->alert_type,
                'message' => $log->message,
                'created_at' => $log->created_at->format('M d, Y H:i:s'),
                'created_at_raw' => $log->created_at->toISOString()
            ];
        }),
        'count' => $logs->count(),
        'last_updated' => now()->format('g:i:s A')
    ]);
});


/*
|--------------------------------------------------------------------------
| OUTAGE DETECTION API
|--------------------------------------------------------------------------
*/

Route::post('/api/outages/check', function() {
    return response()->json(['status' => 'checked']);
});


/*
|--------------------------------------------------------------------------
| TEST NOTIFICATION PAGE
|--------------------------------------------------------------------------
*/

Route::get('/test-push', function() {
    return view('test-push');
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

