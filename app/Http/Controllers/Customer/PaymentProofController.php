<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UploadPaymentProofRequest;
use App\Models\Order;
use App\Services\Payment\PaymentProofService;
use Illuminate\Support\Facades\Auth;

class PaymentProofController extends Controller
{
    public function __construct(private PaymentProofService $paymentProofService) {}

    public function store(UploadPaymentProofRequest $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        try {
            $this->paymentProofService->upload($order, Auth::user(), $request->file('proof'));
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
