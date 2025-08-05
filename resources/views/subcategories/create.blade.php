@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto px-4 py-6">
    <a href="{{ route('subcategories.index') }}" class="inline-block mb-4 text-blue-600 hover:underline">&larr; Back to subcategories</a>
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-xl font-bold mb-4">Add Subcategory</h1>
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('subcategories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Category</label>
                <select name="category_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(old('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
                <a href="{{ route('subcategories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
