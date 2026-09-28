<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'description',
        'price', 'discount_price', 'stock', 'weight',
        'status', 'is_featured', 'image',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function finalPrice(): int
    {
        return $this->discount_price && $this->discount_price < $this->price
            ? $this->discount_price
            : $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isPurchasable(): bool
    {
        return $this->status === 'active' && $this->isInStock();
    }
}
