@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-1 sm:px-4 py-4 sm:py-10">
    <h1 class="text-xl sm:text-3xl font-extrabold mb-4 sm:mb-8 tracking-tight text-center">Product Comparison</h1>
    <div class="sticky top-0 z-20 bg-white pb-2 sm:pb-0 mb-2 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 shadow-none sm:shadow-none">
        <a href="{{ route('products.index') }}" class="w-full sm:w-auto inline-flex items-center gap-2 px-4 sm:px-5 py-2 bg-white border border-gray-300 rounded-full shadow hover:bg-gray-100 text-gray-700 font-semibold transition text-sm sm:text-base justify-center">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Catalog
        </a>
        <button onclick="removeAllCompare()" class="w-full sm:w-auto inline-flex items-center gap-2 px-4 sm:px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full shadow font-semibold transition text-sm sm:text-base justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Remove All
        </button>
    </div>
    <div class="overflow-x-auto pb-2" style="scroll-snap-type: x mandatory;">
        <div class="min-w-full inline-block align-middle">
            <table class="min-w-full bg-white rounded-xl shadow-lg text-xs sm:text-base border border-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-1 sm:px-4 py-2 sm:py-4 text-left text-xs sm:text-lg font-semibold text-gray-700 w-24 sm:w-48">Attribute</th>
                        @foreach($products as $product)
                            <th class="px-1 sm:px-4 py-2 sm:py-4 text-center align-bottom w-40 sm:w-72" style="scroll-snap-align: start;">
                                <form method="GET" action="{{ route('products.compare') }}" onsubmit="event.preventDefault(); window.removeFromCompare({{ $product->id }});">
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold uppercase tracking-wide mb-1 sm:mb-2 transition">Remove</button>
                                </form>
                                <div class="break-all font-bold text-sm sm:text-xl mb-1 sm:mb-3 text-gray-900 leading-tight break-words text-center truncate max-w-[10rem]" title="{{ $product->name }}">{{ $product->name }}</div>
                                <div class="mb-2 sm:mb-4 flex justify-center">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-14 w-14 sm:h-36 sm:w-36 object-contain bg-gray-100 rounded-xl shadow-md border border-gray-200">
                                    @else
                                        <div class="h-14 w-14 sm:h-36 sm:w-36 flex items-center justify-center bg-gray-200 rounded-xl text-gray-400 text-xs sm:text-sm">No Image</div>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700">Price</td>
                        @foreach($products as $product)
                            <td class="px-1 sm:px-4 py-2 sm:py-4 text-center align-middle text-base sm:text-2xl font-extrabold text-green-700">${{ number_format($product->price, 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700">Rating</td>
                        @foreach($products as $product)
                            <td class="px-1 sm:px-4 py-2 sm:py-4 text-center align-middle">
                                @php
                                    $avg = $product->ratings->avg('value');
                                    $full = floor($avg);
                                    $half = ($avg - $full) >= 0.5;
                                @endphp
                                <span class="inline-flex items-center gap-1">
                                    @for($i=0;$i<$full;$i++)
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 20 20"><polygon points="9.9,1.1 12.3,6.8 18.5,7.2 13.7,11.3 15.2,17.2 9.9,14 4.6,17.2 6.1,11.3 1.3,7.2 7.5,6.8 "/></svg>
                                    @endfor
                                    @if($half)
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#fff"/></linearGradient></defs><polygon points="9.9,1.1 12.3,6.8 18.5,7.2 13.7,11.3 15.2,17.2 9.9,14 4.6,17.2 6.1,11.3 1.3,7.2 7.5,6.8 " fill="url(#half)"/></svg>
                                    @endif
                                    @if(!$full && !$half)
                                        <span class="text-gray-400 text-xs sm:text-lg">–</span>
                                    @endif
                                    @if($avg)
                                        <span class="ml-2 text-gray-700 font-semibold text-xs sm:text-lg">{{ number_format($avg, 1) }}</span>
                                    @endif
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700">Category</td>
                        @foreach($products as $product)
                            <td class="break-all px-1 sm:px-4 py-2 sm:py-4 text-center text-xs sm:text-base text-gray-700 truncate max-w-[10rem]" title="{{ $product->subcategory->category->name ?? '-' }}">{{ $product->subcategory->category->name ?? '-' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700">Subcategory</td>
                        @foreach($products as $product)
                            <td class="break-all px-1 sm:px-4 py-2 sm:py-4 text-center text-xs sm:text-base text-gray-700 truncate max-w-[10rem]" title="{{ $product->subcategory->name ?? '-' }}">{{ $product->subcategory->name ?? '-' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700 align-top">Key Features</td>
                        @foreach($products as $product)
                            <td class="px-1 sm:px-4 py-2 sm:py-4 align-top">
                                @if(!empty($product->features))
                                    <ul class="mx-auto max-w-xs text-xs sm:text-sm text-gray-800 flex flex-col items-center justify-center">
                                        @foreach(explode("\n", $product->features) as $feature)
                                            @if(trim($feature))<li class="text-center list-none">{{ $feature }}</li>@endif
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic flex justify-center">No data</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700 align-top">Pros</td>
                        @foreach($products as $product)
                            <td class="px-1 sm:px-4 py-2 sm:py-4 align-top">
                                @if(!empty($product->pros))
                                    <ul class="mx-auto max-w-xs text-green-700 text-xs sm:text-sm flex flex-col items-center justify-center">
                                        @foreach(explode("\n", $product->pros) as $pro)
                                            @if(trim($pro))<li class="text-center list-none">{{ $pro }}</li>@endif
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic flex justify-center">No data</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="font-semibold px-1 sm:px-4 py-2 sm:py-4 text-gray-700 align-top">Cons</td>
                        @foreach($products as $product)
                            <td class="px-1 sm:px-4 py-2 sm:py-4 align-top">
                                @if(!empty($product->cons))
                                    <ul class="mx-auto max-w-xs text-red-700 text-xs sm:text-sm flex flex-col items-center justify-center">
                                        @foreach(explode("\n", $product->cons) as $con)
                                            @if(trim($con))<li class="text-center list-none">{{ $con }}</li>@endif
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic flex justify-center">No data</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="block sm:hidden text-center text-xs text-gray-400 mt-2">Swipe left/right to see all products</div>
    </div>
</div>

@push('scripts')
<script>
window.removeFromCompare = function(productId) {
    let list = JSON.parse(localStorage.getItem('comparisonList') || '[]');
    list = list.filter(id => id !== productId && id !== Number(productId));
    localStorage.setItem('comparisonList', JSON.stringify(list));
    window.comparisonList = list;
    // Reload page with new ids
    if (list.length >= 2) {
        window.location.href = '/products/compare?ids=' + list.join(',');
    } else {
        window.location.href = '/products';
    }
}
window.removeAllCompare = function() {
    localStorage.setItem('comparisonList', '[]');
    window.comparisonList = [];
    window.location.href = '/products';
}
</script>
@endpush
@endsection
