<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Household;
use App\Models\Outage;
use App\Models\StatusLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('analytics');
    }

    public function analytics()
    {
        return view('analytics');
    }

    /**
     * Get monthly power outages data for Chart.js
     */
    public function getMonthlyOutages()
    {
        try {
            // Get outages from the last 12 months using alert_logs table
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $monthlyOutages = DB::table('alert_logs')
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('alert_type', 'OUTAGE')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Create array with all 12 months, filling missing months with 0
            $months = [];
            $counts = [];
            
            for ($i = 0; $i < 12; $i++) {
                $date = Carbon::now()->subMonths(11 - $i);
                $monthName = $date->format('M');
                $monthNumber = $date->month;
                $year = $date->year;
                
                $outage = $monthlyOutages->where('year', $year)->where('month', $monthNumber)->first();
                $count = $outage ? $outage->count : 0;
                
                $months[] = $monthName;
                $counts[] = $count;
            }

            return response()->json([
                'success' => true,
                'labels' => $months,
                'data' => $counts,
                'totalOutages' => array_sum($counts)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load outage data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time outage statistics
     */
       public function getOutageStats()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        return response()->json([
            'success' => true,
            'stats' => [
                'totalOutages' => DB::table('status_logs')->where('status', 'OFF')->count(),

                'today' => DB::table('status_logs')
                    ->where('status', 'OFF')
                    ->whereDate('created_at', $today)
                    ->count(),

                'thisMonth' => DB::table('status_logs')
                    ->where('status', 'OFF')
                    ->whereMonth('created_at', $month)
                    ->count(),

                'lastMonth' => DB::table('status_logs')
                    ->where('status', 'OFF')
                    ->whereMonth('created_at', $lastMonth)
                    ->count(),
            ]
        ]);
    }
    /**
     * Legacy methods for backward compatibility
     */
   public function stats()
{
    $devices = Device::all();

    // AUTO GENERATE OUTAGES
    foreach ($devices as $d) {
        $this->recordOutageIfMissing($d);
    }
}

    public function logs()
    {
        try {
            $logs = StatusLog::with('device')
                ->where('status', 'OFF')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDerivedStatusAttribute()
{
    $thresholdMin = config('services.arduino.heartbeat_timeout_minutes', 1);
    $now = now();

    if (!$this->last_seen) {
        return 'OFF';
    }

    $ageSecs = $now->diffInSeconds($this->last_seen);

    return $ageSecs <= ($thresholdMin * 60) ? 'ON' : 'OFF';
}


   public function weeklyOutageAnalytics()
{
    $now = Carbon::now();

    // -----------------------------
    // GET 4 WEEKS RANGE
    // -----------------------------
    $weeks = [];
    for ($i = 0; $i < 4; $i++) {
        $start = $now->copy()->subWeeks($i)->startOfWeek();
        $end = $now->copy()->subWeeks($i)->endOfWeek();

        $weeks[] = [
            'label' => 'W' . ($i + 1),
            'start' => $start,
            'end' => $end
        ];
    }

    // -----------------------------
    // GET ALL DEVICES
    // -----------------------------
    $devices = DB::table('devices')->get();

    $households = [];

    foreach ($devices as $device) {
        $weekly_counts = [];
        $timeline = [];

        // Weekly counts (per device)
        foreach ($weeks as $w) {
            $count = DB::table('status_logs')
                ->where('device_id', $device->device_id)
                ->where('status', 'OFF')
                ->whereBetween('created_at', [$w['start'], $w['end']])
                ->count();

            $weekly_counts[] = $count;
        }

        // Outage events timeline (last 3)
        $timelineRaw = DB::table('status_logs')
            ->where('device_id', $device->device_id)
            ->where('status', 'OFF')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($timelineRaw as $log) {
            // attempt to find next ON log
            $endLog = DB::table('status_logs')
                ->where('device_id', $device->device_id)
                ->where('status', 'ON')
                ->where('created_at', '>', $log->created_at)
                ->orderBy('created_at')
                ->first();

            $timeline[] = [
                'start' => $log->created_at,
                'end'   => $endLog->created_at ?? null
            ];
        }

        $households[] = [
            'label' => $device->household_name ?? 'Unknown',
            'location' => $device->barangay ?? 'No Location',
            'weekly_counts' => $weekly_counts,
            'outages_total' => array_sum($weekly_counts),
            'timeline' => $timeline,
        ];
    }
 $thisWeekStart = Carbon::now()->startOfWeek();
    $thisWeekEnd   = Carbon::now()->endOfWeek();

    $totalThisWeek = DB::table('status_logs')
    ->where('status', 'OFF')
    ->whereRaw('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')
    ->count();
    // -----------------------------
    // META: MOST AFFECTED HOUSEHOLD
    // -----------------------------
    $top = collect($households)->sortByDesc('outages_total')->first();

    $meta = [
        'total_outages_this_week' => $totalThisWeek,
        'top_household' => $top ? [
            'label' => $top['label'],
            'count' => $top['outages_total'],
        ] : null,
        'updated_at' => now()->toDateTimeString()
    ];

    return response()->json([
        'success' => true,
        'meta' => $meta,
        'households' => $households
    ]);
}
    /**
     * Get weekly outage view data showing Day 1-7 for each week
     */
     public function getWeeklyOutageView()
    {
        $weeks = [];
        for ($i = 0; $i < 4; $i++) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek();
            $end = Carbon::now()->subWeeks($i)->endOfWeek();

            $days = [];
            for ($d = 0; $d < 7; $d++) {
                $day = $start->copy()->addDays($d);

                $count = DB::table('status_logs')
                    ->where('status', 'OFF')
                    ->whereDate('created_at', $day->toDateString())
                    ->count();

                $days[] = [
                    'date' => $day->toDateString(),
                    'day_name' => $day->format('D'),
                    'day_number' => $day->format('d'),
                    'has_outage' => $count > 0,
                    'outage_count' => $count
                ];
            }

            $weeks[$i] = [
                'week_label' => "Week " . ($i + 1),
                'start_formatted' => $start->format('M d'),
                'end_formatted' => $end->format('M d'),
                'total_outages' => array_sum(array_column($days, 'outage_count')),
                'days' => $days
            ];
        }

        return response()->json([
            'success' => true,
            'weeks' => $weeks
        ]);
    }


    protected function syncHouseholdsFromDevices(): void
    {
        Device::select('id', 'device_id', 'household_name', 'barangay', 'status', 'last_seen')
            ->chunkById(100, function ($devices) {
                foreach ($devices as $device) {
                    Household::firstOrCreate(
                        ['device_id' => $device->device_id],
                        [
                            'device_pk' => $device->id,
                            'name' => $device->household_name ?? ('Household '.$device->device_id),
                            'location' => $device->barangay,
                            'last_status' => strtoupper($device->status ?? 'OFF'),
                            'last_seen' => $device->last_seen,
                        ]
                    );
                }
            });
    }
}
