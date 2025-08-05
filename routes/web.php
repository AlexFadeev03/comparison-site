<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'is_admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Resource routes for admin CRUD (web)
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class)->except(['index', 'show']);
    Route::resource('products', App\Http\Controllers\ProductController::class)->except(['index', 'show']);
});

// Only admin can access categories and subcategories
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('subcategories', App\Http\Controllers\SubcategoryController::class);
});

Route::get('/products/compare', [App\Http\Controllers\ProductController::class, 'compare'])->name('products.compare');

Route::resource('products', App\Http\Controllers\ProductController::class)->only(['index', 'show']);

// Rating (user vote for product)
Route::middleware(['auth', 'is_not_admin'])->post('/products/{product}/rate', [\App\Http\Controllers\RatingController::class, 'store'])->name('products.rate');
Route::middleware(['auth', 'is_not_admin'])->delete('/products/{product}/rate', [\App\Http\Controllers\RatingController::class, 'destroy'])->name('products.rate.delete');

Route::fallback(function () {
    abort(404);
});

require __DIR__.'/auth.php';
