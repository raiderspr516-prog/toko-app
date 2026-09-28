<a href="{{ route('products.show', $product) }}" class="bg-white rounded-2xl shadow-sm card-hover overflow-hidden flex flex-col relative">
    @if ($product->discount_price)
        @php $diskonPersen = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
        <span class="absolute top-2 left-2 z-10 bg-gradient-to-r from-orange-500 to-pink-600 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow">
            -{{ $diskonPersen }}%
        </span>
    @endif
    @if ($product->is_featured)
        <span class="absolute top-2 right-2 z-10 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-1 rounded-full shadow">⭐ Unggulan</span>
    @endif

    <div class="h-40 bg-gray-100 flex items-center justify-center overflow-hidden">
        @if ($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
        @else
            <span class="text-4xl">📦</span>
        @endif
    </div>
    <div class="p-3 flex-1 flex flex-col">
        <span class="text-[11px] text-gray-400">{{ $product->category->name }}</span>
        <h4 class="text-sm font-medium text-gray-800 line-clamp-2 leading-snug min-h-[2.5rem]">{{ $product->name }}</h4>
        <div class="mt-auto pt-2">
            @if ($product->discount_price)
                <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($product->price,0,',','.') }}</p>
                <p class="text-pink-600 font-bold">Rp{{ number_format($product->discount_price,0,',','.') }}</p>
            @else
                <p class="text-gray-800 font-bold">Rp{{ number_format($product->price,0,',','.') }}</p>
            @endif
            @if (!$product->isInStock())
                <span class="inline-block mt-1 text-[10px] bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full">Stok Habis</span>
            @else
                <span class="inline-block mt-1 text-[10px] text-gray-400">✅ Stok: {{ $product->stock }}</span>
            @endif
        </div>
    </div>
</a>
