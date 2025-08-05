@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold">Subcategories</h1>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('subcategories.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">+ Add Subcategory</a>
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
                    <th class="px-3 py-2 text-left">Category</th>
                    <th class="px-3 py-2 text-left">Description</th>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <th class="px-3 py-2 text-left">Actions</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @forelse($subcategories as $subcategory)
                    <tr class="border-b last:border-b-0">
                        <td class="break-all max-w-[10rem]" title="{{ $subcategory->name }}">{{ $subcategory->name }}</td>
                        <td class="break-all max-w-[10rem]" title="{{ $subcategory->category->name ?? '-' }}">{{ $subcategory->category->name ?? '-' }}</td>
                        <td class="break-all max-w-[22rem]" title="{{ $subcategory->description }}">{{ $subcategory->description }}</td>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <td class="px-3 py-2 text-center">
                                    <div class="flex flex-row items-center gap-3 justify-center">
                                        <a href="{{ route('subcategories.edit', $subcategory) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536M9 11l6.768-6.768a2.5 2.5 0 113.536 3.536L12.5 17.5a2 2 0 01-1.414.586H7v-4.086a2 2 0 01.586-1.414l7.646-7.646z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" onsubmit="return confirm('Delete subcategory?');" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">No subcategories</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
