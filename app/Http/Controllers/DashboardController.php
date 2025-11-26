<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for the 4 metric cards
     */
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();


            // 1. Monthly Outages - Count outages from alert_logs table for current month
            $monthlyOutages = DB::table('alert_logs')
                ->where('alert_type', 'OUTAGE')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // 2. Online/Offline Devices - Based on devices table and their current status
            $totalDevices = DB::table('devices')->count();
            
            // Get devices that are currently online (last seen within 10 minutes)
            $onlineDevices = DB::table('devices')
                ->where('last_seen', '>=', Carbon::now()->subMinutes(10))
                ->count();
            
            $offlineDevices = $totalDevices - $onlineDevices;

            // 3. Today's Outages - Count outages from alert_logs table for today
            $todayOutages = DB::table('alert_logs')
                ->where('alert_type', 'OUTAGE')
                ->whereDate('created_at', $today)
                ->count();

            $stats = [
                'monthlyOutages' => $monthlyOutages,
                'onlineDevices' => $onlineDevices,
                'offlineDevices' => $offlineDevices,
                'todayOutages' => $todayOutages
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'lastUpdated' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get power outages data for the chart from alert_logs table
     */
    public function getPowerOutagesData()
    {
        try {
            // Get last 12 months of outage events from alert_logs table
            $data = DB::table('alert_logs')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('alert_type', 'OUTAGE')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Create labels and data arrays
            $labels = [];
            $counts = [];

            // Generate all months in the range (Nov 2024 - Oct 2025)
            $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
            for ($i = 0; $i < 12; $i++) {
                $month = $startMonth->copy()->addMonths($i);
                $monthKey = $month->format('Y-m');
                $monthLabel = $month->format('M Y');
                
                $labels[] = $monthLabel;
                
                // Find count for this month
                $monthData = $data->where('month', $monthKey)->first();
                $counts[] = $monthData ? $monthData->count : 0;
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $counts,
                'lastUpdated' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load power outages data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
