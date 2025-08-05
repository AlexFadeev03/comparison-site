@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <a href="{{ url()->previous() }}" class="inline-block mb-4 text-blue-600 hover:underline">&larr; Back</a>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2 break-all max-w-full" title="{{ $category->name }}">{{ $category->name }}</h1>
        <div class="text-gray-600 mb-2 break-all max-w-full" title="{{ $category->description }}">{{ $category->description ?? '-' }}</div>
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="text-gray-500 text-sm">ID: {{ $category->id }}</div>
        @endif
        @auth
            @if(auth()->user()->isAdmin())
                <div class="flex flex-row gap-3 mb-4 mt-6">
                    <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-1.5 rounded shadow transition text-base" title="Edit">Edit</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete category?');" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-1.5 rounded shadow transition text-base" title="Delete">Delete</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Subcategories</h2>
        @if($category->subcategories->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded text-sm table-fixed">
                    <colgroup>
                        <col style="width:10rem;">
                        <col style="width:22rem;">
                        <col style="width:7rem;">
                    </colgroup>
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Description</th>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <th class="px-3 py-2 text-left">Actions</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->subcategories as $subcategory)
                            <tr class="border-b last:border-b-0">
                                <td class="break-all max-w-[10rem] align-top" title="{{ $subcategory->name }}">
                                    {{ $subcategory->name }}
                                </td>
                                <td class="break-all max-w-[22rem] align-top truncate" title="{{ $subcategory->description }}">
                                    <div class="break-all max-w-[22rem] truncate">{{ $subcategory->description }}</div>
                                </td>
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-3 py-2 text-center">
                                            <div class="flex flex-row items-center gap-3 justify-center">
                                                <a href="{{ route('subcategories.edit', $subcategory) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536M9 11l3.536 3.536m3.536-3.536L9 11m0 0l-3.536-3.536m0 0L9 11" /></svg>
                                                </a>
                                                <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" onsubmit="return confirm('Delete subcategory?');" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-gray-400">No subcategories</div>
        @endif
    </div>
</div>
@endsection
