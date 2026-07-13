<?php

namespace App\Http\Controllers;

use App\Ccir;
use App\Ddr;
use App\Drdr;
use App\Ncn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function fetchDashboardData(){
        if(Auth::user()->hasRole('administrator')){
            $ccirs = Ccir::count();
            $drdr = Drdr::count();
            $ncns = Ncn::count();
            $ddr = Ddr::count();
        }
        return response()->json([
            'ccirs' => $ccirs,
            'drdr' => $drdr,
            'ncns' => $ncns,
            'ddr' => $ddr
        ]);
    }
    public function fetchDashboardDataDaily(){
        if(!Auth::user()->hasRole('administrator')){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $today = now()->toDateString();

        $dailyCounts = [
            'DRDR' => Drdr::whereDate('created_at', $today)->count(),
            'DDR' => Ddr::whereDate('created_at', $today)->count(),
            'NCN' => Ncn::whereDate('created_at', $today)->count(),
            'CCIR' => Ccir::whereDate('created_at', $today)->count()
        ];

        return response()->json([
            'counts' => $dailyCounts
        ]);
    }

    public function fetchDashboardDataPerYear($year){
        if(!Auth::user()->hasRole('administrator')){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $counts = [
            'DRDR' => [],
            'DDR' => [],
            'NCN' => [],
            'CCIR' => []
        ];

        for ($month = 1; $month <= 12; $month++) {
            $counts['DRDR'][] = Drdr::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $counts['DDR'][] = Ddr::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $counts['NCN'][] = Ncn::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
            $counts['CCIR'][] = Ccir::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        }

        return response()->json([
            'year' => $year,
            'months' => $months,
            'counts' => $counts
        ]);
    }

    public function yearDropdown(){
        // if(!Auth::user()->hasRole('administrator')){
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }
        $startYear = 2018; // last year data of EFORMS
        $currentYear = now()->year;
        
        $years = [];
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }

        return response()->json([
            'years' => $years
        ]);
    }
}
