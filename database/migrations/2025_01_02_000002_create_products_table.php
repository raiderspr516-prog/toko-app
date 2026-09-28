<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount_price')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('weight')->default(0); // gram, untuk shipping
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('image')->nullable(); // gambar utama
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
