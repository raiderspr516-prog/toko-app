<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            $data['slug'] = $this->uniqueSlug($data['name']);

            if ($image) {
                $data['image'] = $image->store('products', 'public');
            }

            $product = Product::create($data);

            if ($product->stock > 0) {
                InventoryMovement::record($product, 'stock_in', $product->stock, 'Stok awal produk dibuat');
            }

            return $product;
        });
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($product, $data, $image) {
            if ($data['name'] !== $product->name) {
                $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
            }

            $stockBefore = $product->stock;

            if ($image) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $image->store('products', 'public');
            }

            $product->update($data);

            $diff = $product->stock - $stockBefore;
            if ($diff !== 0) {
                InventoryMovement::record(
                    $product,
                    $diff > 0 ? 'stock_in' : 'adjustment',
                    $diff,
                    'Penyesuaian stok manual oleh admin'
                );
            }

            return $product;
        });
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        $product->delete(); // soft delete — histori order tetap valid
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}
