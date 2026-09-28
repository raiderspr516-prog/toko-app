<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function subtotal(): int
    {
        return $this->items->sum(fn (CartItem $item) => $item->product->finalPrice() * $item->quantity);
    }

    public function totalQuantity(): int
    {
        return $this->items->sum('quantity');
    }
}
