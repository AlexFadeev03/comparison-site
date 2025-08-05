@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <a href="{{ url()->previous() }}" class="inline-block mb-4 text-blue-600 hover:underline">&larr; Back</a>
    <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col sm:flex-row gap-8 items-center">
        <div class="w-full sm:w-72 flex-shrink-0">
            @if($product->image)
                @php
                    $img = $product->image;
                    $isExternal = Str::startsWith($img, ['http://', 'https://']);
                    $isStorage = Str::startsWith($img, ['/storage', 'storage']);
                @endphp
                <img src="{{ $isExternal ? $img : ($isStorage ? asset(ltrim($img, '/')) : asset('storage/' . $img)) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-64 object-contain rounded-xl border border-gray-200 bg-gray-50" />
            @else
                <div class="w-full h-48 flex items-center justify-center bg-gray-100 rounded-xl border border-gray-200 text-gray-400">No image</div>
            @endif
            @auth
                @php
                    $userRating = $product->ratings->where('user_id', auth()->id())->first();
                @endphp
                @if(!auth()->user()->isAdmin())
                    <div class="mb-3 flex flex-col items-start" id="star-vote-block">
                        <label class="block text-sm text-gray-700 mb-1">Оцініть цей товар:</label>
                        @if($userRating)
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="h-5 w-5 star-svg {{ $i <= $userRating->value ? 'text-yellow-400' : 'text-gray-300' }} transition-colors duration-150" fill="currentColor" viewBox="0 0 20 20">
                                            <polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/>
                                        </svg>
                                    @endfor
                                </div>
                                <form action="{{ route('products.rate.delete', $product) }}" method="POST" class="flex items-center gap-2 ml-2" id="remove-vote-form" data-ajax-form>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-1.5 rounded-full bg-gray-200 text-gray-600 font-semibold text-sm shadow hover:bg-gray-300 transition border border-gray-300">Remove vote</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('products.rate', $product) }}" method="POST" class="flex items-center gap-2" id="star-rate-form">
                                @csrf
                                <div class="flex items-center gap-1" id="star-rating-js">
                                    @for($i=1; $i<=5; $i++)
                                        <button type="button" data-value="{{ $i }}" class="group focus:outline-none star-btn">
                                            <svg class="h-5 w-5 star-svg text-gray-300 transition-colors duration-150" fill="currentColor" viewBox="0 0 20 20">
                                                <polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="value" id="star-value" value="1">
                                <button type="submit" class="px-4 py-1.5 rounded-full bg-yellow-400 text-white font-semibold text-sm shadow hover:bg-yellow-500 transition">Vote</button>
                            </form>
                        @endif
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            attachVoteHandlers();
                        });
                        function attachVoteHandlers() {
                            const stars = document.querySelectorAll('#star-rating-js .star-btn');
                            const starValue = document.getElementById('star-value');
                            const voteForm = document.getElementById('star-rate-form');
                            const removeForm = document.getElementById('remove-vote-form');
                            if (removeForm) {
                                console.log('Remove vote action:', removeForm.action);
                            }
                            let current = 1;
                            function paintStars(val) {
                                if (!stars) return;
                                stars.forEach((btn, idx) => {
                                    btn.querySelector('svg').classList.toggle('text-yellow-400', idx < val);
                                    btn.querySelector('svg').classList.toggle('text-gray-300', idx >= val);
                                });
                            }
                            if (voteForm) {
                                stars.forEach(btn => {
                                    btn.addEventListener('mouseenter', function() {
                                        paintStars(btn.dataset.value);
                                    });
                                    btn.addEventListener('mouseleave', function() {
                                        paintStars(current);
                                    });
                                    btn.addEventListener('click', function() {
                                        current = btn.dataset.value;
                                        starValue.value = current;
                                        paintStars(current);
                                    });
                                });
                                voteForm.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    fetch(voteForm.action, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                        body: JSON.stringify({ value: starValue.value })
                                    })
                                    .then(resp => resp.json())
                                    .then(data => {
                                        if(data && data.success) {
                                            updateVoteBlock();
                                            updateAverageRating();
                                        }
                                    });
                                });
                            }
                            if (removeForm) {
                                removeForm.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    fetch(removeForm.action, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                        },
                                    }).then(resp => {
                                        if(resp.ok) {
                                            updateVoteBlock();
                                            updateAverageRating();
                                        }
                                    });
                                });
                            }
                        }
                        function updateVoteBlock() {
                            fetch(window.location.href, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                            .then(resp => resp.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newVoteBlock = doc.querySelector('#star-vote-block');
                                const curVoteBlock = document.querySelector('#star-vote-block');
                                if (newVoteBlock && curVoteBlock) {
                                    curVoteBlock.innerHTML = newVoteBlock.innerHTML;
                                    attachVoteHandlers();
                                }
                            });
                        }
                        function updateAverageRating() {
                            fetch(window.location.href, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                            .then(resp => resp.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                // Оновлюємо лише блок рейтингу
                                const newBlock = doc.querySelector('[data-rating-block]');
                                const curBlock = document.querySelector('[data-rating-block]');
                                if (newBlock && curBlock) {
                                    curBlock.innerHTML = newBlock.innerHTML;
                                }
                            });
                        }
                    </script>
                @endif
            @endauth
        </div>
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2 break-all max-w-full" title="{{ $product->name }}">{{ $product->name }}</h1>
            <div class="flex flex-wrap gap-3 mb-3">
                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded font-semibold break-all max-w-full" title="{{ $product->subcategory->category->name ?? '-' }}">Category: {{ $product->subcategory->category->name ?? '-' }}</span>
                <span class="inline-block bg-pink-100 text-pink-800 text-sm px-3 py-1 rounded font-semibold break-all max-w-full" title="{{ $product->subcategory->name ?? '-' }}">Subcategory: {{ $product->subcategory->name ?? '-' }}</span>
            </div>
            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="text-gray-400 text-sm mb-2">Category ID: {{ $product->subcategory->category->id }}</div>
            @endif
            <div class="mb-2">
                <span class="font-semibold text-lg">Price:</span> {{ number_format($product->price, 2) }}
            </div>
            <div class="mb-2">
                <span class="font-semibold">Description:</span>
                <span class="break-all max-w-full">{{ $product->description }}</span>
            </div>
            <div class="mb-2 flex items-center gap-2" data-rating-block>
                <span class="text-gray-800 font-semibold">Rating:</span>
                <span class="text-lg font-bold">{{ number_format($product->averageRating(), 2) }}</span>
                <span class="flex items-center">
                    @php
                        $rating = $product->averageRating();
                        $fullStars = floor($rating);
                        $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) < 0.75;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    @for($i = 0; $i < $fullStars; $i++)
                        <svg class="inline h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/></svg>
                    @endfor
                    @if($halfStar)
                        <svg class="inline h-5 w-5 text-yellow-400" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="#facc15"/><stop offset="50%" stop-color="#e5e7eb"/></linearGradient></defs><polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7" fill="url(#half)"/></svg>
                    @endif
                    @for($i = 0; $i < $emptyStars; $i++)
                        <svg class="inline h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><polygon points="10,1 12,7 19,7 13.5,11 15.5,18 10,14 4.5,18 6.5,11 1,7 8,7"/></svg>
                    @endfor
                </span>
                <span class="text-sm text-gray-500">({{ $product->ratings->count() }})</span>
            </div>
            <div class="mb-2">
                <span class="font-semibold">Features:</span>
                <div class="break-all max-w-full">{{ $product->features }}</div>
            </div>
            <div class="mb-2">
                <span class="font-semibold text-green-700">Pros:</span>
                <span class="text-green-700 break-all max-w-full">{{ $product->pros }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold text-red-600">Cons:</span>
                <span class="text-red-600 break-all max-w-full">{{ $product->cons }}</span>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="flex gap-4 mt-4">
                        <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
