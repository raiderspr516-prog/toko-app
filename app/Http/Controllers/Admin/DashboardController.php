<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function index()
    {
        $overview = $this->reportService->overview();
        $salesChart = $this->reportService->salesChart(14);
        $topProducts = $this->reportService->topProducts(5);
        $recentOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('overview', 'salesChart', 'topProducts', 'recentOrders'));
    }
}
