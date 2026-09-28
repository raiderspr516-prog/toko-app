<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function overview(): array
    {
        return [
            'total_products' => \App\Models\Product::count(),
            'total_categories' => \App\Models\Category::count(),
            'total_customers' => User::count(),
            'total_orders' => Order::count(),
            'new_orders' => Order::where('status', 'pending')->count(),
            'waiting_payment' => Order::where('status', 'waiting_payment')->count(),
            'payment_review' => Order::where('status', 'payment_review')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('grand_total'),
        ];
    }

    /**
     * Data penjualan harian selama N hari terakhir — dipakai untuk grafik dashboard.
     */
    public function salesChart(int $days = 14): array
    {
        $rows = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $data[] = (int) ($rows[$date] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function topProducts(int $limit = 5)
    {
        return OrderItem::select('product_name_snapshot')
            ->selectRaw('SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['paid', 'processing', 'shipped', 'completed']))
            ->groupBy('product_name_snapshot')
            ->orderByDesc('total_qty')
            ->take($limit)
            ->get();
    }

    public function topCustomers(int $limit = 5)
    {
        return Order::select('user_id')
            ->selectRaw('COUNT(*) as total_orders, SUM(grand_total) as total_spent')
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->with('user')
            ->take($limit)
            ->get();
    }

    /**
     * Ringkasan penjualan per periode: daily/weekly/monthly/yearly.
     * Dikelompokkan di PHP (bukan raw SQL DATE_FORMAT) supaya portable
     * antara MySQL (production) dan SQLite (default development).
     */
    public function salesByPeriod(string $period = 'monthly')
    {
        $orders = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->where('created_at', '>=', now()->subMonths(12))
            ->get(['created_at', 'grand_total']);

        $format = match ($period) {
            'daily' => 'Y-m-d',
            'weekly' => 'Y-\WW',
            'yearly' => 'Y',
            default => 'Y-m',
        };

        return $orders->groupBy(fn ($o) => $o->created_at->format($format))
            ->map(fn ($group, $key) => (object) [
                'period' => $key,
                'total_orders' => $group->count(),
                'total_revenue' => $group->sum('grand_total'),
            ])
            ->sortKeysDesc()
            ->take(12)
            ->values();
    }
}
