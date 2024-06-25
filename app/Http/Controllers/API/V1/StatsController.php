<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{

    public function index()
    {
        $totalEarning = Order::sum('total');
        $percentageOfEarningToday = $totalEarning = 0 ? 0 : Order::whereDate('created_at', date('Y-m-d'))->sum('total') / $totalEarning * 100;

        $totalOrders = Order::count();
        $totalOrdersPercentageToday = $totalOrders = 0 ? 0 : Order::whereDate('created_at', date('Y-m-d'))->count() / $totalOrders * 100;

        $totalCustomers = User::role('client')->count();
        $totlaCustomersPercentageToday = $totalCustomers = 0 ? 0 : User::role('client')->whereDate('created_at', date('Y-m-d'))->count() / $totalCustomers * 100;

        $recntOrders = Order::orderBy('id', 'desc')->take(5)->get();

        $ordersByCity = Order::select('city', DB::raw('COUNT(*) as total_orders'), DB::raw('(COUNT(*) / '.$totalOrders.') * 100 as percentage'))
        ->groupBy('city')
        ->get();    

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        $ordersByDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $endDate = Carbon::now()->endOfWeek(); // End of the current week
        $startDate = Carbon::now()->subWeeks(6)->startOfWeek(); // Start of the week 7 weeks ago
        
        // Get the number of orders for each week within the last 7 weeks
        $ordersByWeek = Order::select(DB::raw('YEAR(created_at) as year'), DB::raw('WEEK(created_at) as week'), DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('WEEK(created_at)'))
            ->get();

        $endDate = Carbon::now()->endOfMonth(); // End of the current month
        $startDate = Carbon::now()->subMonths(6)->startOfMonth(); // Start of the month 7 months ago
        
        // Get the number of orders for each month within the last 7 months
        $ordersByMonth = Order::select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();

        return $this->respondOk([
            'totalEarning' => $totalEarning,
            'percentageOfEarningToday' => $percentageOfEarningToday,
            'totalOrders' => $totalOrders,
            'totalOrdersPercentageToday' => $totalOrdersPercentageToday,
            'totalCustomers' => $totalCustomers,
            'totlaCustomersPercentageToday' => $totlaCustomersPercentageToday,
            'recntOrders' => $recntOrders,
            'ordersByCity' => $ordersByCity,
            'ordersByDay' => $ordersByDay,
            'ordersByWeek' => $ordersByWeek,
            'ordersByMonth' => $ordersByMonth
        ]);
    }
}
