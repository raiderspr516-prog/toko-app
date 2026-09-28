<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong, tidak bisa checkout.');
        }

        $addresses = $user->addresses()->latest()->get();

        return view('customer.checkout.index', compact('cart', 'addresses'));
    }

    public function store(CheckoutRequest $request)
    {
        try {
            $order = $this->orderService->createFromCart(Auth::user(), $request->validated());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('checkout.payment', $order)->with('success', 'Order berhasil dibuat! Lanjutkan pembayaran.');
    }
}
