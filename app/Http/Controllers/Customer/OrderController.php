<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $query = Auth::user()->orders()->with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product', 'address', 'payment', 'shipment');

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

        try {
            $this->orderService->cancel($order, $request->input('reason', 'Dibatalkan oleh customer'));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
