<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderCompleted;
use App\Events\OrderShipped;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function __construct(private AuditLogService $auditLogService) {}

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'courier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
        ]);

        if (! in_array($order->status, ['paid', 'processing'], true)) {
            return back()->with('error', 'Order harus berstatus paid/processing sebelum bisa dikirim.');
        }

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'courier' => $validated['courier'],
                'tracking_number' => $validated['tracking_number'],
                'status' => 'shipped',
                'shipped_at' => now(),
            ]
        );

        $order->update(['status' => 'shipped']);

        $this->auditLogService->log('ship_order', $order, [], ['courier' => $validated['courier'], 'resi' => $validated['tracking_number']]);

        event(new OrderShipped($order->fresh()));

        return back()->with('success', 'Order ditandai sebagai dikirim.');
    }

    public function markDelivered(Order $order)
    {
        if (! $order->shipment || $order->status !== 'shipped') {
            return back()->with('error', 'Order belum berstatus dikirim.');
        }

        $order->shipment->update(['status' => 'delivered', 'delivered_at' => now()]);
        $order->update(['status' => 'completed']);

        $this->auditLogService->log('complete_order', $order, ['status' => 'shipped'], ['status' => 'completed']);

        event(new OrderCompleted($order->fresh()));

        return back()->with('success', 'Order ditandai selesai.');
    }
}
