@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">Categories</h1>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">+ Add Category</a>
            @endif
        @endauth
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Name</th>
                    <th class="px-3 py-2 text-left">Description</th>
                    <th class="px-3 py-2 text-center">Subcategories</th>
                    <th class="px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b last:border-b-0">
                        <td class="break-all max-w-[10rem]" title="{{ $category->name }}">{{ $category->name }}</td>
                        <td style="word-break:break-all; white-space:normal" class="align-middle w-[22rem] min-w-[10rem]" title="{{ $category->description }}">
                            <div class="break-all max-w-[22rem] inline-block">
                                {{ $category->description }}
                            </div>
                        </td>
                        <td class="px-3 py-2 text-center">{{ $category->subcategories_count }}</td>
                        <td class="px-3 py-2 text-center">
                            <div class="flex flex-row items-center gap-3 justify-center">
                                <a href="{{ route('categories.show', $category) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <ellipse cx="12" cy="12" rx="8" ry="5" stroke-width="1" stroke="currentColor" fill="none"/>
                                        <circle cx="12" cy="12" r="2" stroke-width="1" stroke="currentColor" fill="none"/>
                                    </svg>
                                </a>
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16.862 3.487a2.06 2.06 0 012.915 2.914l-1.293 1.293-2.914-2.914 1.292-1.293zM5.25 17.25v-2.086c0-.265.105-.52.293-.707l8.379-8.379 2.914 2.914-8.38 8.379a1 1 0 01-.707.293H5.25z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete category?');" style="display:inline">
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
                    <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">No categories</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
