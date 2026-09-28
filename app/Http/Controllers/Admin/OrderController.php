<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditLogService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private AuditLogService $auditLogService,
    ) {}

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cari')) {
            $query->where('order_number', 'like', '%'.$request->cari.'%');
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'address', 'items.product', 'payment.proofs', 'shipment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['processing', 'completed', 'cancelled'])],
            'cancelled_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $oldStatus = $order->status;

        if ($validated['status'] === 'cancelled') {
            $this->orderService->cancel($order, $validated['cancelled_reason'] ?? 'Dibatalkan oleh admin');
        } else {
            $order->update(['status' => $validated['status']]);
        }

        $this->auditLogService->log('update_order_status', $order, ['status' => $oldStatus], ['status' => $validated['status']]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}
