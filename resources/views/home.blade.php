@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="flex flex-col items-center mb-10">
            <div class="flex items-center gap-4 mb-2">
                <img src="{{ asset('storage/seed_images/spiral-3.svg') }}" alt="Vortex Logo" class="h-12 w-12">
                <span class="text-4xl font-extrabold tracking-tight text-gray-900">Vortex</span>
            </div>
            <div class="text-lg text-blue-700 font-semibold mb-1">Your Guide to the Best Choices</div>
            <div class="text-gray-500 text-base mb-2">Discover, compare and choose top products in every category</div>
        </div>

        <!-- Categories -->
        <div x-data="vortex()" class="">
            <h2 class="text-2xl font-bold mb-6 text-center">Categories</h2>
            <div class="flex flex-col gap-4 mb-8 md:hidden">
                @foreach($categories as $category)
                    <div>
                        <button @click="toggleCategory({{ $category['id'] }})"
                            :class="selectedCategory === {{ $category['id'] }} ? 'border-pink-400 bg-pink-50' : 'bg-white'"
                            class="w-full flex items-center justify-between rounded-xl border border-gray-200 px-4 py-4 shadow-sm transition focus:outline-none">
                            <span class="flex items-center gap-3">
                                <span class="text-2xl">{!! $category['icon'] ?? '📦' !!}</span>
                                <span class="font-semibold text-lg text-gray-900">{{ $category['name'] }}</span>
                            </span>
                            <span :class="selectedCategory === {{ $category['id'] }} ? 'text-pink-500' : 'text-gray-400'" class="text-2xl font-bold">
                                <template x-if="selectedCategory === {{ $category['id'] }}">–</template>
                                <template x-if="selectedCategory !== {{ $category['id'] }}">+</template>
                            </span>
                        </button>
                        <div x-show="selectedCategory === {{ $category['id'] }}" x-transition class="pt-2 pb-2 px-2">
                            <template x-for="sub in filteredSubcategories({{ $category['id'] }})" :key="sub.id">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white rounded-lg shadow p-3 mb-2">
                                    <div class="font-semibold text-gray-900 text-base mb-1 sm:mb-0" x-text="sub.name"></div>
                                    <div class="flex gap-3 mt-1 sm:mt-0">
                                        <a :href="'/subcategories/' + sub.id" class="inline-flex items-center gap-1 text-base font-medium text-gray-600 hover:text-pink-600 transition">
                                            Explore <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                        <a :href="'/products?subcategory_id=' + sub.id + '&compare=1&sort=rating&direction=desc'" class="inline-flex items-center gap-1 text-base font-medium text-pink-500 hover:text-pink-700 transition">
                                            Compare <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="hidden md:flex gap-4 mb-8 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @foreach($categories as $category)
                    <button @click="toggleCategory({{ $category['id'] }})"
                        :class="selectedCategory === {{ $category['id'] }} ? 'border-2 border-pink-400 shadow-md' : 'border border-gray-200 shadow'"
                        class="bg-white rounded-2xl p-5 flex-1 flex flex-col items-center gap-2 transition hover:shadow-lg focus:outline-none min-w-0">
                        <span class="text-4xl">{!! $category['icon'] ?? '📦' !!}</span>
                        <span class="font-semibold text-gray-900">{{ $category['name'] }}</span>
                    </button>
                @endforeach
            </div>
            <div class="hidden md:block mb-8">
                <template x-if="selectedCategory">
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-4 text-center">Subcategories</h3>
                        <div class="flex flex-wrap gap-5 justify-center">
                            <template x-for="sub in filteredSubcategories(selectedCategory)" :key="sub.id">
                                <div class="bg-white rounded-xl shadow p-4 w-60 flex flex-col items-center">
                                    <span class="text-3xl mb-2" x-text="sub.icon ?? '🧩'"></span>
                                    <div class="font-semibold text-gray-800 mb-1" x-text="sub.name"></div>
                                    <div class="text-gray-500 text-sm mb-3 text-center truncate" :title="sub.description" x-text="sub.description && sub.description.length > 18 ? sub.description.slice(0, 18) + '…' : sub.description"></div>
                                    <div class="flex gap-3 mt-auto">
                                        <a :href="'/subcategories/' + sub.id" class="inline-flex items-center gap-1 text-base font-medium text-gray-600 hover:text-pink-600 transition">
                                            Explore <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                        <a :href="'/products?subcategory_id=' + sub.id + '&compare=1&sort=rating&direction=desc'" class="inline-flex items-center gap-1 text-base font-medium text-pink-500 hover:text-pink-700 transition">
                                            Compare <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Top 10 Trending Products -->
        <div class="max-w-2xl mx-auto mb-16">
            <h2 class="text-2xl font-bold mb-5">Top 10 Trending Products</h2>
            <div>
                @foreach($trendingProducts as $idx => $product)
                    <div class="mb-8">
                        <div class="w-full px-6 py-5 bg-white rounded-xl shadow border border-gray-100 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-0
                            @if($idx === 0) ring-2 ring-yellow-400 border-yellow-200 @elseif($idx === 1) ring-2 ring-gray-300 border-gray-200 @elseif($idx === 2) ring-2 ring-amber-700 border-amber-300 @endif">
                            <span class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-xl mr-4
                                @if($idx === 0) bg-yellow-400 text-white shadow-lg @elseif($idx === 1) bg-gray-400 text-white shadow-md @elseif($idx === 2) bg-amber-700 text-white shadow-md @else bg-orange-300 text-white @endif">
                                @if($idx === 0)🥇@elseif($idx === 1)🥈@elseif($idx === 2)🥉@else{{ $idx + 1 }}@endif
                            </span>
                            <div class="flex-1">
                                <span class="text-lg font-semibold text-gray-900 block">{{ $product->name }}</span>
                                <span class="ml-1 text-base text-gray-500">
                                    @if($product->subcategory && $product->subcategory->category)
                                        ({{ $product->subcategory->category->name }})
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                <span class="flex items-center gap-1 text-base">
                                    <span class="text-yellow-400 font-bold">★</span>
                                    <span class="font-semibold text-gray-700">{{ number_format($product->avg_rating, 1) }}</span>
                                </span>
                                <span class="text-xs text-gray-500">({{ $product->ratings_count }})</span>
                                <a href="/products/{{ $product->id }}" class="text-blue-700 text-sm font-semibold hover:underline flex items-center">Learn more <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function vortex() {
    return {
        selectedCategory: null,
        subcategories: @json($subcategories),
        toggleCategory(id) {
            this.selectedCategory = this.selectedCategory === id ? null : id;
        },
        filteredSubcategories(categoryId) {
            return this.subcategories.filter(s => s.category_id === categoryId);
        }
    }
}
</script>
@endsection
