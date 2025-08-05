<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Middleware\IsAdmin;
use Illuminate\Routing\Attributes\Middleware;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductFilterRequest;

#[Middleware('is_admin')]
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductFilterRequest $request)
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        $subcategories = \App\Models\Subcategory::orderBy('name')->get();

        $validated = $request->validated();
        if (isset($validated['min_price'], $validated['max_price']) && $validated['min_price'] > $validated['max_price']) {
            return view('products.index', [
                'products' => collect(),
                'categories' => $categories,
                'subcategories' => $subcategories,
            ])->withErrors(['min_price' => 'Min price must be less than or equal to max price']);
        }

        $query = Product::with(['subcategory.category']);
        $query->filterCategory($request->input('category_id'));
        $query->filterSubcategory($request->input('subcategory_id'));
        $query->filterPrice($request->input('min_price'), $request->input('max_price'));
        // Сортування
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');
        if ($sort === 'price') {
            $query->sortByPrice($direction);
        } elseif ($sort === 'rating') {
            $query->sortByRating($direction);
        } else {
            $query->sortByName($direction);
        }
        $products = $query->get();
        return view('products.index', compact('products', 'categories', 'subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        $subcategories = \App\Models\Subcategory::with('category')->orderBy('name')->get();
        return view('products.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = \App\Models\Product::with(['subcategory.category'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::orderBy('name')->get();
        $subcategories = Subcategory::with('category')->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            // Видалити стару картинку, якщо є
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Видалити картинку з диска
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted!');
    }

    /**
     * Compare products side by side.
     */
    public function compare(Request $request)
    {
        $ids = collect(explode(',', $request->query('ids', '')))
            ->filter(fn($id) => is_numeric($id))
            ->unique()
            ->take(3)
            ->values();
        if ($ids->count() < 2) {
            return redirect()->route('products.index')->with('error', 'Select at least 2 products to compare.');
        }
        $products = Product::with(['subcategory.category', 'ratings'])
            ->whereIn('id', $ids)
            ->get();
        if ($products->count() < 2) {
            return redirect()->route('products.index')->with('error', 'Products not found for comparison.');
        }
        return view('products.compare', compact('products'));
    }
}
