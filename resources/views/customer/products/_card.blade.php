<a href="{{ route('products.show', $product) }}" class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden flex flex-col">
    <div class="h-40 bg-gray-100 flex items-center justify-center">
        @if ($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
        @else
            <span class="text-4xl">📦</span>
        @endif
    </div>
    <div class="p-3 flex-1 flex flex-col">
        <span class="text-xs text-gray-400">{{ $product->category->name }}</span>
        <h4 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $product->name }}</h4>
        <div class="mt-auto pt-2">
            @if ($product->discount_price)
                <p class="text-xs text-gray-400 line-through">Rp{{ number_format($product->price,0,',','.') }}</p>
                <p class="text-red-600 font-bold">Rp{{ number_format($product->discount_price,0,',','.') }}</p>
            @else
                <p class="text-gray-800 font-bold">Rp{{ number_format($product->price,0,',','.') }}</p>
            @endif
            @if (!$product->isInStock())
                <span class="text-xs text-red-500">Stok Habis</span>
            @endif
        </div>
    </div>
</a>
