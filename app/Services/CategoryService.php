<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    public function create(array $data): Category
    {
        $data['slug'] = $this->uniqueSlug($data['name']);

        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        if ($category->products()->exists()) {
            throw new \RuntimeException('Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete(); // soft delete
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}
