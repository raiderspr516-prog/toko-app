<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (! $order->payment) {
            $this->paymentService->createForOrder($order);
            $order->refresh();
        }

        $gatewayResult = $this->paymentService->getGatewayResultFor($order);

        return view('customer.payment.show', compact('order', 'gatewayResult'));
    }
}
