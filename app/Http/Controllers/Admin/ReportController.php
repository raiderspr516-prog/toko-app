<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $sales = $this->reportService->salesByPeriod($period);

        return view('admin.reports.sales', compact('sales', 'period'));
    }

    public function products()
    {
        $topProducts = $this->reportService->topProducts(20);

        return view('admin.reports.products', compact('topProducts'));
    }

    public function customers()
    {
        $topCustomers = $this->reportService->topCustomers(20);

        return view('admin.reports.customers', compact('topCustomers'));
    }
}
