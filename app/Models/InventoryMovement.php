<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id', 'type', 'quantity', 'reference_type', 'reference_id', 'note', 'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Helper cepat untuk mencatat pergerakan stok dari mana saja
     * (ProductService, OrderService, dsb) — konsisten satu pintu.
     */
    public static function record(
        Product $product,
        string $type,
        int $quantity,
        ?string $note = null,
        $reference = null
    ): self {
        return self::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'note' => $note,
            'created_by' => Auth::guard('admin')->id(),
        ]);
    }
}
