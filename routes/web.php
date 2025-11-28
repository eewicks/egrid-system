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
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

// Show login form
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

// Login POST
Route::post('/admin-login', [AdminAuthController::class, 'postlogin'])
    ->name('admin.login.submit');

// Logout
Route::post('/admin/logout', function(Request $request) {
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboardtest');

    Route::get('/admin/api/stats', [AdminDashboardController::class, 'stats'])
        ->name('admin.api.stats');

    Route::get('/admin/api/logs', [AdminDashboardController::class, 'logs'])
        ->name('admin.api.logs');

    Route::get('/admin/api/device-status', [AdminDashboardController::class, 'deviceStatus'])
        ->name('admin.api.device_status');

    Route::get('/admin/api/devices', [AdminDashboardController::class, 'getDevices'])
        ->name('admin.api.devices');
});


/*
|--------------------------------------------------------------------------
| DEVICES CRUD
|--------------------------------------------------------------------------
*/
Route::resource('devices', DeviceController::class);


/*
|--------------------------------------------------------------------------
| Public testing routes
|--------------------------------------------------------------------------
*/
Route::get('/api/devices', [AdminDashboardController::class, 'getDevices'])
    ->name('api.devices');


/*
|--------------------------------------------------------------------------
| Dashboard Test Page (optional)
|--------------------------------------------------------------------------
*/
Route::get('/dashboardtest', function () {
    return view('dashboardtest');
});


/*
|--------------------------------------------------------------------------
| Analytics Routes
|--------------------------------------------------------------------------
*/
Route::get('/analytics', [AnalyticsController::class, 'analytics'])
    ->name('analytics.index');

// Charts / graph data
Route::get('/analytics/stats', [AnalyticsController::class, 'stats'])
    ->name('analytics.stats');

Route::get('/analytics/logs', [AnalyticsController::class, 'logs'])
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
| Dashboard Stats (extra)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])
    ->name('dashboard.stats');

Route::get('/api/power-outages', [DashboardController::class, 'getPowerOutagesData'])
    ->name('api.power-outages');


/*
|--------------------------------------------------------------------------
| Alert Settings
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
| Backup & Recovery
|--------------------------------------------------------------------------
*/
Route::get('/backup-recovery', [BackupRecoveryController::class, 'index'])
    ->name('backup_recovery.index');


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
| Alert Logs API
|--------------------------------------------------------------------------
*/
Route::get('/api/alert-logs', function () {
    if (!session('admin_logged_in')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $logs = \App\Models\AlertLog::latest()->take(10)->get();

    return response()->json([
        'logs' => $logs->map(function ($log) {
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
| SMS API
|--------------------------------------------------------------------------
*/
Route::prefix('api/sms')->group(function () {
    Route::post('/test', [SMSController::class, 'testSMS']);
    Route::post('/outage-alert', [SMSController::class, 'sendOutageAlert']);
    Route::post('/simulate-outage', [SMSController::class, 'simulateOutage']);
    Route::get('/balance', [SMSController::class, 'getBalance']);
});
