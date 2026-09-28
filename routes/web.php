<?php

use App\Http\Controllers\Customer\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Entry point utama. Route customer & admin dipisah ke file masing-masing
| supaya mudah dikelola seiring bertambahnya fitur di phase berikutnya.
|
*/

Route::get('/', function () {
    $featured = Product::with('category')->active()->where('is_featured', true)->take(8)->get();
    $latest = Product::with('category')->active()->latest()->take(8)->get();

    return view('home', compact('featured', 'latest'));
})->name('home');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');

require __DIR__.'/customer.php';
require __DIR__.'/admin.php';
