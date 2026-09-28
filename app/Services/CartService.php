<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * Tambah produk ke cart. Selalu validasi ulang produk & stok di sini —
     * jangan pernah percaya harga/ketersediaan dari frontend.
     */
    public function add(User $user, Product $product, int $quantity): void
    {
        $this->assertPurchasable($product, $quantity);

        DB::transaction(function () use ($user, $product, $quantity) {
            $cart = $this->getOrCreateCart($user);

            $item = $cart->items()->where('product_id', $product->id)->first();
            $newQuantity = $item ? $item->quantity + $quantity : $quantity;

            $this->assertPurchasable($product, $newQuantity);

            $cart->items()->updateOrCreate(
                ['product_id' => $product->id],
                ['quantity' => $newQuantity, 'price_snapshot' => $product->finalPrice()]
            );
        });
    }

    public function updateQuantity(User $user, Product $product, int $quantity): void
    {
        $this->assertPurchasable($product, $quantity);

        $cart = $this->getOrCreateCart($user);
        $cart->items()->where('product_id', $product->id)->update([
            'quantity' => $quantity,
            'price_snapshot' => $product->finalPrice(),
        ]);
    }

    public function remove(User $user, Product $product): void
    {
        $cart = $this->getOrCreateCart($user);
        $cart->items()->where('product_id', $product->id)->delete();
    }

    public function clear(User $user): void
    {
        $this->getOrCreateCart($user)->items()->delete();
    }

    private function assertPurchasable(Product $product, int $quantity): void
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Jumlah minimal 1.');
        }

        if ($product->status !== 'active') {
            throw new \RuntimeException("Produk \"{$product->name}\" sudah tidak tersedia.");
        }

        if ($product->stock < $quantity) {
            throw new \RuntimeException("Stok \"{$product->name}\" tidak mencukupi. Sisa stok: {$product->stock}.");
        }
    }
}
