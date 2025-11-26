<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Auth;
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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});



// Admin login form
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// Handle login
Route::post('/admin-login', [AdminAuthController::class, 'postlogin'])->name('admin.login.submit');

// Protect admin routes with session check
Route::middleware([])->group(function () {
    Route::get('/admin/dashboard', function () {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        return app(AdminDashboardController::class)->index();
    })->name('admin.dashboardtest');

    Route::get('/admin/api/stats', function () {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return app(AdminDashboardController::class)->stats();
    })->name('admin.api.stats');

    Route::get('/admin/api/logs', function () {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return app(AdminDashboardController::class)->logs();
    })->name('admin.api.logs');
});

// Device status nga mo display
        Route::get('/admin/api/device-status', function () {
            if (!session('admin_logged_in')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return app(AdminDashboardController::class)->deviceStatus();
        })->name('admin.api.device_status');

// Devices list for dashboard (JSON) — returns devices with display status
Route::get('/admin/api/devices', function () {
    if (!session('admin_logged_in')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    return app(AdminDashboardController::class)->getDevices();
})->name('admin.api.devices');

// Public devices API for testing (remove in production)
Route::get('/api/devices', [AdminDashboardController::class, 'getDevices'])->name('api.devices');

// Logout
Route::post('/admin/logout', function(Request $request){
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');

// General logout route
Route::post('/logout', function(Request $request){
    $request->session()->forget('admin_logged_in');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

//Device
    Route::resource('devices', DeviceController::class);


    //Test Sample Dashboard
   Route::get('/dashboardtest', function() {
    return view('dashboardtest');
});

//Analytics


//Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics.index');

// JSON endpoints for charts/table
Route::get('/analytics/stats', [AnalyticsController::class, 'stats'])->name('analytics.stats');
Route::get('/analytics/logs',  [AnalyticsController::class, 'logs'])->name('analytics.logs');

// New analytics endpoints
Route::get('/analytics/monthly-outages', [AnalyticsController::class, 'getMonthlyOutages'])->name('analytics.monthly-outages');
Route::get('/analytics/outage-stats', [AnalyticsController::class, 'getOutageStats'])->name('analytics.outage-stats');

Route::get('/analytics/weekly-devices', [AnalyticsController::class, 'weeklyOutageAnalytics'])
    ->name('analytics.weekly-devices');

Route::get('/analytics/weekly-outage-view', [AnalyticsController::class, 'getWeeklyOutageView'])
    ->name('analytics.weekly-outage-view');

Route::get('/admin/api/device-status', [AdminDashboardController::class, 'deviceStatus'])
    ->name('admin.api.device_status');

Route::get('/admin/api/device-status', [AdminDashboardController::class, 'deviceStatus'])
     ->name('admin.api.device_status');

// Dashboard API routes
Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
Route::get('/api/power-outages', [DashboardController::class, 'getPowerOutagesData'])->name('api.power-outages');

// Alert Settings routes
Route::get('/settings/alerts', [AlertSettingsController::class, 'index'])->name('settings.alerts');
Route::post('/settings/alerts/save', [AlertSettingsController::class, 'store'])->name('settings.alerts.save');
Route::post('/settings/alerts/test', [AlertSettingsController::class, 'testAlert'])->name('settings.alerts.test');

// Backup & Recovery routes
Route::get('/backup-recovery', [BackupRecoveryController::class, 'index'])->name('backup_recovery.index');

// Test route
Route::get('/test-alerts', function() {
    return view('test-alerts');
});

// Web Push Notification API routes
Route::prefix('api/webpush')->group(function () {
    Route::get('/vapid-public-key', [WebPushController::class, 'getVapidPublicKey']);
    Route::post('/subscribe', [WebPushController::class, 'subscribe']);
    Route::post('/unsubscribe', [WebPushController::class, 'unsubscribe']);
    Route::post('/resubscribe', [WebPushController::class, 'resubscribe']);
    Route::post('/test', [WebPushController::class, 'testNotification']);
});

// Alert Logs API
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

// Outage detection API
Route::post('/api/outages/check', function() {
    // This endpoint can be called by the service worker for background checks
    return response()->json(['status' => 'checked']);
});

// Test route for push notifications
Route::get('/test-push', function() {
    return view('test-push');
});

// SMS API routes
Route::prefix('api/sms')->group(function () {
    Route::post('/test', [SMSController::class, 'testSMS']);
    Route::post('/outage-alert', [SMSController::class, 'sendOutageAlert']);
    Route::post('/simulate-outage', [SMSController::class, 'simulateOutage']);
    Route::get('/balance', [SMSController::class, 'getBalance']);
});
