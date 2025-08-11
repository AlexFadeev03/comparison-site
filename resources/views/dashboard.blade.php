<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-10 px-4 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <div class="flex flex-col items-center mb-10">
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ asset('storage/seed_images/spiral-3.svg') }}" alt="Vortex Logo" class="h-12 w-12">
                        <span class="text-4xl font-extrabold tracking-tight text-gray-900">Vortex Dashboard</span>
                    </div>
                    <div class="text-lg text-blue-700 font-semibold mb-1">Your Admin Control Center</div>
                    <div class="text-gray-500 text-base mb-2">Insights, quick actions, and stats for your comparison platform</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center border-t-4 border-pink-300">
                        <span class="text-5xl mb-3">📦</span>
                        <div class="text-xl font-bold mb-1">Products</div>
                        <div class="text-3xl font-mono text-gray-700">{{ \App\Models\Product::count() }}</div>
{{--                        TODO: Повиносити моделі з вьюх в репозиторії, отримувати дані в контролері з репозиторію і передавати во вьюху  --}}
                    </div>
                    <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center border-t-4 border-blue-300">
                        <span class="text-5xl mb-3">🗂️</span>
                        <div class="text-xl font-bold mb-1">Categories</div>
                        <div class="text-3xl font-mono text-gray-700">{{ \App\Models\Category::count() }}</div>
{{--                        TODO: Повиносити моделі з вьюх в репозиторії, отримувати дані в контролері з репозиторію і передавати во вьюху  --}}
                    </div>
                    <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center border-t-4 border-yellow-300">
                        <span class="text-5xl mb-3">🧩</span>
                        <div class="text-xl font-bold mb-1">Subcategories</div>
                        <div class="text-3xl font-mono text-gray-700">{{ \App\Models\Subcategory::count() }}</div>
{{--                        TODO: Повиносити моделі з вьюх в репозиторії, отримувати дані в контролері з репозиторію і передавати во вьюху  --}}
                    </div>
                    <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-center border-t-4 border-green-300">
                        <span class="text-5xl mb-3"><svg xmlns='http://www.w3.org/2000/svg' class='inline h-8 w-8 text-blue-400' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z' /></svg></span>
                        <div class="text-xl font-bold mb-1">Users</div>
                        <div class="text-3xl font-mono text-gray-700">{{ \App\Models\User::count() }}</div>
{{--                        TODO: Повиносити моделі з вьюх в репозиторії, отримувати дані в контролері з репозиторію і передавати во вьюху  --}}
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 text-pink-600 flex items-center gap-2"><svg class="h-5 w-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Recent Votes</h3>
                    <ul class="divide-y divide-gray-100 text-sm">
                        @foreach(\App\Models\Rating::with('product')->where('user_id', Auth::id())->latest()->take(5)->get() as $vote)
                            <li class="py-2 flex flex-col">
                                <div>
                                    <span class="font-semibold text-gray-800">{{ $vote->product->name ?? 'Product deleted' }}</span>
                                    <span class="text-yellow-500 ml-2">Voted: {{ $vote->value }}/5</span>
                                    <span class="text-gray-400 text-xs ml-2">{{ $vote->updated_at->diffForHumans() }}</span>
                                </div>
                                @if($vote->product)
                                    <a href="/products/{{ $vote->product->id }}" class="mt-1 inline-flex items-center gap-1 text-pink-600 font-semibold hover:underline text-xs whitespace-nowrap">Move to <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="text-lg font-bold mb-4 text-blue-600 flex items-center gap-2"><svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 4v16h16V4H4zm2 2h12v12H6V6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> Recently Edited</h3>
                    <ul class="divide-y divide-gray-100 text-sm">
                        @foreach(collect([
                            ...\App\Models\Product::latest('updated_at')->take(2)->get(),
                            ...\App\Models\Category::latest('updated_at')->take(1)->get(),
                            ...\App\Models\Subcategory::latest('updated_at')->take(2)->get(),
                        ])->sortByDesc('updated_at')->take(5) as $item)
                            <li class="py-2 flex flex-col">
                                <div>
                                    <span class="font-semibold text-gray-800 break-all max-w-full">
                                        @if($item instanceof \App\Models\Product)
                                            Product: {{ $item->name }}
                                        @elseif($item instanceof \App\Models\Category)
                                            Category: {{ $item->name }}
                                        @elseif($item instanceof \App\Models\Subcategory)
                                            Subcategory: {{ $item->name }}
                                        @endif
                                    </span>
                                    <span class="text-gray-400 text-xs ml-2">Updated {{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                                @if($item instanceof \App\Models\Product)
                                    <a href="/products/{{ $item->id }}" class="mt-1 inline-flex items-center gap-1 text-blue-600 font-semibold hover:underline text-xs whitespace-nowrap">Move to <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                                @elseif($item instanceof \App\Models\Category)
                                    <a href="/categories/{{ $item->id }}" class="mt-1 inline-flex items-center gap-1 text-blue-600 font-semibold hover:underline text-xs whitespace-nowrap">Move to <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                                @elseif($item instanceof \App\Models\Subcategory)
                                    <a href="/subcategories/{{ $item->id }}" class="mt-1 inline-flex items-center gap-1 text-blue-600 font-semibold hover:underline text-xs whitespace-nowrap">Move to <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
