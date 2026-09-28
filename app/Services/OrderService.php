<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Models\Address;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CouponService $couponService) {}

    /**
     * Buat order dari isi cart user. SEMUA harga & stok divalidasi ulang
     * di sini terhadap database — tidak pernah percaya angka dari request.
     *
     * @param  array{address_id:int, notes?:string, coupon_code?:string}  $data
     */
    public function createFromCart(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = $user->cart()->with('items.product')->first();

            if (! $cart || $cart->items->isEmpty()) {
                throw new \RuntimeException('Keranjang belanja kosong.');
            }

            $address = Address::where('user_id', $user->id)->findOrFail($data['address_id']);

            $subtotal = 0;
            $itemsToCreate = [];
            $totalWeight = 0;

            foreach ($cart->items as $cartItem) {
                // Lock baris produk supaya tidak overselling saat request bersamaan.
                $product = Product::whereKey($cartItem->product_id)->lockForUpdate()->first();

                if (! $product || $product->status !== 'active') {
                    throw new \RuntimeException("Produk \"{$cartItem->product->name}\" sudah tidak tersedia. Silakan perbarui keranjang.");
                }

                if ($product->stock < $cartItem->quantity) {
                    throw new \RuntimeException("Stok \"{$product->name}\" tidak mencukupi (sisa {$product->stock}). Silakan perbarui keranjang.");
                }

                $price = $product->finalPrice(); // harga TERBARU dari DB, bukan snapshot cart
                $lineSubtotal = $price * $cartItem->quantity;
                $subtotal += $lineSubtotal;
                $totalWeight += $product->weight * $cartItem->quantity;

                $itemsToCreate[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $shippingCost = $this->calculateShippingCost($totalWeight, $subtotal);

            $discountAmount = 0;
            $coupon = null;
            if (! empty($data['coupon_code'])) {
                $coupon = $this->couponService->findValid($data['coupon_code'], $subtotal);
                $discountAmount = $this->couponService->calculateDiscount($coupon, $subtotal);
            }

            $grandTotal = max(0, $subtotal + $shippingCost - $discountAmount);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'payment_fee' => 0,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'coupon_id' => $coupon?->id,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($itemsToCreate as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'product_name_snapshot' => $line['product']->name,
                    'product_price_snapshot' => $line['price'],
                    'quantity' => $line['quantity'],
                    'subtotal' => $line['subtotal'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
                InventoryMovement::record($line['product'], 'order_reserved', -$line['quantity'], "Dipesan via order {$order->order_number}", $order);
            }

            if ($coupon) {
                $coupon->increment('used_count');
                $coupon->usages()->create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discountAmount,
                ]);
            }

            $cart->items()->delete();

            event(new OrderCreated($order));

            return $order;
        });
    }

    /**
     * Batalkan order (oleh customer, hanya jika masih diizinkan) —
     * kembalikan stok yang sudah direservasi.
     */
    public function cancel(Order $order, string $reason, ?string $by = 'customer'): Order
    {
        return DB::transaction(function () use ($order, $reason) {
            if (! $order->isCancellableByCustomer() && $order->status !== 'waiting_payment') {
                throw new \RuntimeException('Order ini sudah tidak bisa dibatalkan.');
            }

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    InventoryMovement::record($item->product, 'order_cancelled', $item->quantity, "Order {$order->order_number} dibatalkan", $order);
                }
            }

            $order->update(['status' => 'cancelled', 'cancelled_reason' => $reason]);

            return $order;
        });
    }

    /**
     * Ongkos kirim sederhana (placeholder) — flat rate berbasis berat total,
     * gratis ongkir di atas ambang belanja tertentu. Siap diganti dengan
     * integrasi kurir asli (RajaOngkir dkk) tanpa mengubah OrderService dari luar.
     */
    private function calculateShippingCost(int $totalWeightGram, int $subtotal): int
    {
        if ($subtotal >= 500000) {
            return 0; // gratis ongkir
        }

        $kg = max(1, (int) ceil($totalWeightGram / 1000));

        return 9000 + (($kg - 1) * 3000); // Rp9.000 kg pertama, +Rp3.000/kg berikutnya
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Y').'-'.random_int(100000, 999999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
