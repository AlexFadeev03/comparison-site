@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto px-4 py-6">
    <a href="{{ route('products.index') }}" class="inline-block mb-4 text-blue-600 hover:underline">&larr; Back to product list</a>
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">Add Product</h1>
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required />
            </div>
            <x-category-subcategory-select
                :categories="$categories"
                :subcategories="$subcategories"
                categoryName="category_id"
                subcategoryName="subcategory_id"
                categoryId="category-create-{{ uniqid() }}"
                subcategoryId="subcategory-create-{{ uniqid() }}"
                :selectedCategory="old('category_id')"
                :selectedSubcategory="old('subcategory_id')"
            />
            <div class="mb-4">
                <label for="image" class="block font-medium mb-1">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="border rounded w-full px-3 py-2">
                @error('image')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Price</label>
                <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" class="w-full border rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Features</label>
                <textarea name="features" rows="2" class="w-full border rounded px-3 py-2">{{ old('features') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Pros</label>
                <textarea name="pros" rows="2" class="w-full border rounded px-3 py-2">{{ old('pros') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Cons</label>
                <textarea name="cons" rows="2" class="w-full border rounded px-3 py-2">{{ old('cons') }}</textarea>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
