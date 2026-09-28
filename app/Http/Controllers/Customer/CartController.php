<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(Auth::user());
        $cart->load('items.product.category');

        return view('customer.cart.index', compact('cart'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        try {
            $this->cartService->add(Auth::user(), $product, (int) $request->quantity);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1']]);

        try {
            $this->cartService->updateQuantity(Auth::user(), $product, (int) $request->quantity);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->cartService->remove(Auth::user(), $product);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        $this->cartService->clear(Auth::user());

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
