@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">Product List</h1>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">+ Add Product</a>
            @endif
        @endauth
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="GET" action="" class="mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex flex-wrap gap-4 grow">
            <x-category-subcategory-select
                :categories="$categories"
                :subcategories="$subcategories"
                categoryName="category_id"
                subcategoryName="subcategory_id"
                categoryId="category-index-{{ uniqid() }}"
                subcategoryId="subcategory-index-{{ uniqid() }}"
                :selectedCategory="request('category_id')"
                :selectedSubcategory="request('subcategory_id')"
            />
            <div>
                <label class="block text-xs font-semibold mb-1">Min price</label>
                <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" class="border rounded px-2 py-1 w-24">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Max price</label>
                <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" class="border rounded px-2 py-1 w-24">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Sort by</label>
                <select name="sort" class="border rounded px-2 py-1 w-32">
                    <option value="name" @if(request('sort')=='name') selected @endif>Name</option>
                    <option value="price" @if(request('sort')=='price') selected @endif>Price</option>
                    <option value="rating" @if(request('sort')=='rating') selected @endif>Rating</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Direction</label>
                <select name="direction" class="border rounded px-2 py-1 w-24">
                    <option value="asc" @if(request('direction')=='asc') selected @endif>Asc</option>
                    <option value="desc" @if(request('direction')=='desc') selected @endif>Desc</option>
                </select>
            </div>
        </div>
        <div class="flex items-end min-w-[220px] gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-center">Reset</a>
        </div>
    </form>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow text-sm table-fixed">
            <colgroup>
                <col style="width:32rem;">
                <col style="width:14rem;">
                <col style="width:14rem;">
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Name</th>
                    <th class="px-3 py-2 text-left">Category</th>
                    <th class="px-3 py-2 text-left">Subcategory</th>
                    <th class="px-3 py-2 text-left">Image</th>
                    <th class="px-3 py-2 text-right">Price</th>
                    <th class="px-3 py-2 text-right">Rating</th>
                    <th class="px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b last:border-b-0">
                        <td style="word-break:break-all; white-space:normal" class="align-middle w-[32rem] min-w-[16rem]" title="{{ $product->name }}">
                            <div class="break-all max-w-[32rem] inline-block">
                                {{ $product->name }}
                            </div>
                        </td>
                        <td style="word-break:break-all; white-space:normal" class="align-middle w-[14rem] min-w-[8rem]" title="{{ $product->subcategory->category->name ?? '-' }}">
                            <div class="break-all max-w-[14rem] inline-block">
                                {{ $product->subcategory->category->name ?? '-' }}
                            </div>
                        </td>
                        <td style="word-break:break-all; white-space:normal" class="align-middle w-[14rem] min-w-[8rem]" title="{{ $product->subcategory->name ?? '-' }}">
                            <div class="break-all max-w-[14rem] inline-block">
                                {{ $product->subcategory->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="Image" class="h-12 w-12 object-cover rounded border">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right">{{ $product->price }}</td>
                        <td class="px-3 py-2 text-right">
                            <span class="text-yellow-400 whitespace-nowrap">
                                @php $rating = $product->averageRating(); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($rating))
                                        <svg class="inline h-4 w-4 fill-current" viewBox="0 0 20 20"><polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/></svg>
                                    @elseif($i - $rating > 0 && $i - $rating < 1)
                                        <svg class="inline h-4 w-4" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half_{{$product->id}}_{{$i}}">
                                                    <stop offset="50%" stop-color="#facc15"/>
                                                    <stop offset="50%" stop-color="#e5e7eb"/>
                                                </linearGradient>
                                            </defs>
                                            <polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7" fill="url(#half_{{$product->id}}_{{$i}})"/>
                                        </svg>
                                    @else
                                        <svg class="inline h-4 w-4 fill-gray-300" viewBox="0 0 20 20"><polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/></svg>
                                    @endif
                                @endfor
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <div class="flex flex-row items-center gap-3 justify-center">
                                <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <ellipse cx="12" cy="12" rx="8" ry="5" stroke-width="1" stroke="currentColor" fill="none"/>
                                        <circle cx="12" cy="12" r="2" stroke-width="1" stroke="currentColor" fill="none"/>
                                    </svg>
                                </a>
                                <a href="#"
                                   class="compare-btn text-blue-600 hover:text-blue-800"
                                   data-id="{{ $product->id }}"
                                   title="Compare"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <ellipse cx="12" cy="12" rx="8" ry="5" stroke-width="1" stroke="currentColor" fill="none"/>
                                        <circle cx="12" cy="12" r="2" stroke-width="1" stroke="currentColor" fill="none"/>
                                    </svg>
                                </a>
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16.862 3.487a2.06 2.06 0 012.915 2.914l-1.293 1.293-2.914-2.914 1.292-1.293zM5.25 17.25v-2.086c0-.265.105-.52.293-.707l8.379-8.379 2.914 2.914-8.38 8.379a1 1 0 01-.707.293H5.25z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product?');" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-6 text-center text-gray-400">No products</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Floating Compare Button -->
<div id="floating-compare" class="fixed z-50 bottom-10 right-10 flex flex-col items-end" style="right:44px; bottom:44px;">
    <div class="relative flex items-center justify-center w-14 h-14 bg-green-500 rounded-full shadow-lg cursor-pointer transition-all duration-200 group" id="compare-circle" style="min-width:56px; min-height:56px;">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" class="text-white">
            <circle cx="12" cy="12" r="10" fill="#22c55e"/>
            <path d="M12 8v8M8 12h8" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
        <span id="compare-count" class="absolute -top-1 -right-1 bg-white text-green-600 rounded-full px-2 py-0.5 text-xs font-bold border border-green-500">0</span>
        <button id="compare-clear" class="absolute -right-2 bottom-0 w-5 h-5 flex items-center justify-center bg-red-500 rounded-full shadow text-white hover:bg-red-600 transition-all duration-200" title="Clear comparison list">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="5" fill="#ef4444"/><path d="M3 3l4 4M7 3l-4 4" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.comparisonList = JSON.parse(localStorage.getItem('comparisonList') || '[]');
    function toggleCompare(productId) {
        let list = JSON.parse(localStorage.getItem('comparisonList') || '[]');
        if (list.includes(productId)) {
            list = list.filter(id => id !== productId);
        } else {
            if (list.length >= 3) {
                alert('You can compare up to 3 products.');
                return;
            }
            list.push(productId);
        }
        localStorage.setItem('comparisonList', JSON.stringify(list));
        window.comparisonList = list;
        document.dispatchEvent(new CustomEvent('comparison-updated'));
    }
    function updateCompareBtns() {
        document.querySelectorAll('.compare-btn').forEach(btn => {
            const id = Number(btn.dataset.id);
            if (window.comparisonList.includes(id)) {
                btn.classList.remove('bg-white','text-green-600','border-green-500');
                btn.classList.add('bg-red-500','text-white','border-red-600');
                btn.setAttribute('title', 'Remove from comparison');
                btn.querySelector('svg').innerHTML = '<circle cx="12" cy="12" r="10" fill="#ef4444"/><path d="M8 12h8" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>';
            } else {
                btn.classList.remove('bg-red-500','text-white','border-red-600');
                btn.classList.add('bg-white','text-green-600','border-green-500');
                btn.setAttribute('title', 'Add to comparison');
                btn.querySelector('svg').innerHTML = '<circle cx="12" cy="12" r="10" fill="#22c55e"/><path d="M12 8v8M8 12h8" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>';
            }
        });
    }
    function updateFloatingCompare() {
        const list = window.comparisonList || [];
        const count = list.length;
        document.getElementById('compare-count').textContent = count;
    }
    function openCompare() {
        const list = window.comparisonList || [];
        if (list.length >= 2 && list.length <= 3) {
            window.location.href = '/products/compare?ids=' + list.join(',');
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        // Event delegation for all .compare-btn clicks
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.compare-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.compare-btn');
                console.log('[COMPARE] Clicked:', btn.dataset.id); // DEBUG LOG
                toggleCompare(Number(btn.dataset.id));
                btn.blur();
            }
        });
        updateCompareBtns();
        updateFloatingCompare();
        document.getElementById('compare-circle').addEventListener('click', openCompare);
        document.getElementById('compare-clear').addEventListener('click', function() {
            localStorage.removeItem('comparisonList');
            window.comparisonList = [];
            document.dispatchEvent(new CustomEvent('comparison-updated'));
        });
        document.addEventListener('comparison-updated', () => {
            updateCompareBtns();
            updateFloatingCompare();
        });
    });
</script>
@endpush
