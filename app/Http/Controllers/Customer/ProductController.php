<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->filled('cari')) {
            $query->where('name', 'like', '%'.$request->cari.'%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('harga_min')) {
            $query->where('price', '>=', (int) $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('price', '<=', (int) $request->harga_max);
        }

        if ($request->boolean('stok_tersedia')) {
            $query->inStock();
        }

        match ($request->get('urutkan')) {
            'harga_asc' => $query->orderBy('price', 'asc'),
            'harga_desc' => $query->orderBy('price', 'desc'),
            'terlaris' => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->status === 'active', 404);

        $product->load('category', 'images');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'related'));
    }
}
