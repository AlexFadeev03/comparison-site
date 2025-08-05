@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] text-center bg-gray-50 pt-16 mt-12">
    <h1 class="text-[7rem] font-extrabold text-blue-600 drop-shadow-lg animate-pulse mb-2 select-none">404</h1>
    <h2 class="text-3xl font-bold mb-4 text-gray-900">Page not found</h2>
    <p class="mb-8 text-lg text-gray-600 max-w-xl mx-auto">It may have been deleted, moved, or never existed.<br>But don’t worry — go back to the homepage and discover something new!</p>
    <a href="{{ url('/') }}" class="w-full max-w-xs sm:max-w-md md:max-w-lg px-10 py-5 bg-blue-600 text-white rounded-full shadow-2xl hover:bg-blue-700 transition text-1xl font-bold focus:outline-none focus:ring-4 focus:ring-blue-300 active:scale-95 mt-2">Go to Home</a>
</div>
@endsection
