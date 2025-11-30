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
        try {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonthStart = Carbon::now()->copy()->subMonth()->startOfMonth();
            $lastMonthEnd = $thisMonth->copy()->subSecond();

            $stats = [
                'today' => Outage::whereDate('started_at', $today)->count(),
                'thisMonth' => Outage::whereBetween('started_at', [$thisMonth, Carbon::now()])->count(),
                'lastMonth' => Outage::whereBetween('started_at', [$lastMonthStart, $lastMonthEnd])->count(),
                'totalOutages' => Outage::count(),
                'totalDevices' => Device::count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Legacy methods for backward compatibility
     */
    public function stats()
    {
        return $this->getOutageStats();
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

    public function weeklyOutageAnalytics()
    {
        try {
            $this->syncHouseholdsFromDevices();

            $now = Carbon::now();
            $weeks = collect(range(0, 3))->map(function ($offset) use ($now) {
                $position = 3 - $offset;
                $start = $now->copy()->startOfWeek(Carbon::MONDAY)->subWeeks($position);
                $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

                return [
                    'label' => 'W'.($offset + 1),
                    'iso_week' => $start->isoWeek(),
                    'iso_year' => $start->isoWeekYear(),
                    'start' => $start,
                    'end' => $end,
                ];
            });

            $rangeStart = $weeks->first()['start']->copy()->startOfDay();
            $rangeEnd = $weeks->last()['end']->copy()->endOfDay();

            $households = Household::withCount('outages as outages_total')
                ->with(['outages' => function ($query) use ($rangeStart, $rangeEnd) {
                    $query->whereBetween('started_at', [$rangeStart, $rangeEnd])
                        ->orderByDesc('started_at');
                }])
                ->orderBy('name')
                ->get();

            $householdPayload = $households->map(function (Household $household) use ($weeks) {
                $weeklyCounts = $weeks->map(function ($week) use ($household) {
                    return $household->outages
                        ->filter(function ($outage) use ($week) {
                            return $outage->started_at &&
                                $outage->started_at->betweenIncluded($week['start'], $week['end']);
                        })
                        ->count();
                })->values();

                $timeline = $household->outages->map(function (Outage $outage) {
                    return [
                        'start' => optional($outage->started_at)->toIso8601String(),
                        'end' => optional($outage->ended_at)->toIso8601String(),
                        'duration_minutes' => $outage->duration_minutes,
                        'duration_human' => $outage->duration_human,
                        'status' => $outage->status,
                    ];
                });

                return [
                    'id' => $household->id,
                    'label' => $household->display_label,
                    'location' => $household->location,
                    'weekly_counts' => $weeklyCounts,
                    'outages_total' => $household->outages_total,
                    'timeline' => $timeline,
                ];
            });

            $currentWeek = $weeks->last();
            $totalThisWeek = Outage::whereBetween('started_at', [$currentWeek['start'], $currentWeek['end']])->count();

            $topHousehold = Outage::select('household_id', DB::raw('COUNT(*) as total'))
                ->whereBetween('started_at', [$rangeStart, $rangeEnd])
                ->groupBy('household_id')
                ->orderByDesc('total')
                ->first();

            $topHouseholdPayload = null;
            if ($topHousehold) {
                $household = $households->firstWhere('id', $topHousehold->household_id)
                    ?? Household::find($topHousehold->household_id);

                if ($household) {
                    $topHouseholdPayload = [
                        'id' => $household->id,
                        'label' => $household->display_label,
                        'count' => (int) $topHousehold->total,
                    ];
                }
            }

            $logs = Outage::with('household')
                ->orderByDesc('started_at')
                ->limit(12)
                ->get()
                ->map(function (Outage $outage) {
                    return [
                        'household' => $outage->household?->display_label,
                        'start' => optional($outage->started_at)->toIso8601String(),
                        'end' => optional($outage->ended_at)->toIso8601String(),
                        'duration_minutes' => $outage->duration_minutes,
                        'status' => $outage->status,
                    ];
                });

            return response()->json([
                'success' => true,
                'meta' => [
                    'weeks' => $weeks->map(function ($week) {
                        return [
                            'label' => $week['label'],
                            'iso_week' => $week['iso_week'],
                            'iso_year' => $week['iso_year'],
                            'start' => $week['start']->toIso8601String(),
                            'end' => $week['end']->toIso8601String(),
                        ];
                    }),
                    'total_outages_this_week' => $totalThisWeek,
                    'top_household' => $topHouseholdPayload,
                    'updated_at' => now()->toIso8601String(),
                ],
                'households' => $householdPayload,
                'logs' => $logs,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load outage analytics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get weekly outage view data showing Day 1-7 for each week
     */
    public function getWeeklyOutageView()
    {
        try {
            $now = Carbon::now();
            $weeks = collect(range(0, 3))->map(function ($offset) use ($now) {
                $position = 3 - $offset;
                $start = $now->copy()->startOfWeek(Carbon::MONDAY)->subWeeks($position);
                $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

                // Get outages for this week
                $outages = Outage::whereBetween('started_at', [
                    $start->copy()->startOfDay(),
                    $end->copy()->endOfDay()
                ])->get();

                // Initialize days array (Day 1 = Monday, Day 7 = Sunday)
                $days = [];
                for ($day = 1; $day <= 7; $day++) {
                    $dayDate = $start->copy()->addDays($day - 1);
                    $dayOutages = $outages->filter(function ($outage) use ($dayDate) {
                        return $outage->started_at && 
                               $outage->started_at->format('Y-m-d') === $dayDate->format('Y-m-d');
                    });

                    $days[] = [
                        'day_number' => $day,
                        'date' => $dayDate->format('Y-m-d'),
                        'day_name' => $dayDate->format('D'),
                        'has_outage' => $dayOutages->count() > 0,
                        'outage_count' => $dayOutages->count(),
                    ];
                }

                return [
                    'week_label' => 'Week ' . ($offset + 1),
                    'iso_week' => $start->isoWeek(),
                    'iso_year' => $start->isoWeekYear(),
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'start_formatted' => $start->format('M d'),
                    'end_formatted' => $end->format('M d, Y'),
                    'days' => $days,
                    'total_outages' => $outages->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'weeks' => $weeks,
                'updated_at' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load weekly outage view',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get weekly outage view with barangay percentages
     * Percentage = (outage occurrences / 7 days) * 100
     */
    public function getWeeklyOutageViewBarangay()
    {
        try {
            $now = Carbon::now();
            
            // Get all unique barangays from devices table
            $allBarangays = Device::whereNotNull('barangay')
                ->where('barangay', '!=', '')
                ->distinct()
                ->pluck('barangay')
                ->toArray();

            $weeks = collect(range(0, 3))->map(function ($offset) use ($now, $allBarangays) {
                $position = 3 - $offset;
                $start = $now->copy()->startOfWeek(Carbon::MONDAY)->subWeeks($position);
                $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

                // Get outages for this week with device/household info
                $outages = Outage::with('household')
                    ->whereBetween('started_at', [
                        $start->copy()->startOfDay(),
                        $end->copy()->endOfDay()
                    ])->get();

                // Initialize days array (Day 1 = Monday, Day 7 = Sunday)
                $days = [];
                for ($day = 1; $day <= 7; $day++) {
                    $dayDate = $start->copy()->addDays($day - 1);
                    $dayOutages = $outages->filter(function ($outage) use ($dayDate) {
                        return $outage->started_at && 
                               $outage->started_at->format('Y-m-d') === $dayDate->format('Y-m-d');
                    });

                    // Get barangays affected on this day
                    $affectedBarangays = $dayOutages->groupBy(function ($outage) {
                        return $outage->household?->location ?? 'Unknown';
                    })->map(function ($outagesGroup, $barangay) {
                        return [
                            'name' => $barangay,
                            'outage_count' => $outagesGroup->count(),
                        ];
                    })->values();

                    $days[] = [
                        'day_number' => $day,
                        'date' => $dayDate->format('Y-m-d'),
                        'day_name' => $dayDate->format('D'),
                        'has_outage' => $dayOutages->count() > 0,
                        'outage_count' => $dayOutages->count(),
                        'affected_barangays' => $affectedBarangays,
                    ];
                }

                // Calculate barangay outage percentages
                // Group outages by barangay (from household location)
                $outagesByBarangay = $outages->groupBy(function ($outage) {
                    return $outage->household?->location ?? 'Unknown';
                });

                // Build barangay list with all barangays from database
                $barangayOutages = collect($allBarangays)->map(function ($barangay) use ($outagesByBarangay) {
                    $outagesGroup = $outagesByBarangay->get($barangay, collect());
                    $outageCount = $outagesGroup->count();
                    // Percentage = (outage occurrences / 7 days) * 100
                    $percentage = ($outageCount / 7) * 100;
                    
                    return [
                        'name' => $barangay,
                        'outage_count' => $outageCount,
                        'percentage' => round($percentage, 1),
                    ];
                })->sortByDesc('percentage')->values();

                return [
                    'week_label' => 'Week ' . ($offset + 1),
                    'iso_week' => $start->isoWeek(),
                    'iso_year' => $start->isoWeekYear(),
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'start_formatted' => $start->format('M d'),
                    'end_formatted' => $end->format('M d, Y'),
                    'days' => $days,
                    'total_outages' => $outages->count(),
                    'barangays' => $barangayOutages,
                ];
            });

            return response()->json([
                'success' => true,
                'weeks' => $weeks,
                'all_barangays' => $allBarangays,
                'updated_at' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load weekly outage view',
                'error' => $e->getMessage()
            ], 500);
        }
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
