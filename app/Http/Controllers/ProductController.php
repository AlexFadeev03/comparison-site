<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Routing\Attributes\Middleware;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductFilterRequest;

#[Middleware('is_admin')]
class ProductController extends Controller
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductFilterRequest $request)
    {
        $categories = \App\Models\Category::orderBy('name')->get(); // TODO:  винести в репозиторій
        $subcategories = \App\Models\Subcategory::orderBy('name')->get(); // TODO:  винести в репозиторій

        $filters = $request->all();
        $products = $this->productRepository->getFilteredList($filters, ['subcategory.category']);

        return view('products.index', compact('products', 'categories', 'subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get(); // TODO:  винести в репозиторій
        $subcategories = \App\Models\Subcategory::with('category')->orderBy('name')->get(); // TODO:  винести в репозиторій
        return view('products.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $this->productRepository->createItem($validated, $request->file('image'));
        return redirect()->route('products.index')->with('success', 'Product added!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = $this->productRepository->getItemOrFail($id, ['subcategory.category']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = $this->productRepository->getItemOrFail($id);
        $categories = \App\Models\Category::orderBy('name')->get(); // TODO:  винести в репозиторій
        $subcategories = Subcategory::with('category')->orderBy('name')->get();  // TODO:  винести в репозиторій
        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $validated = $request->validated();
        $this->productRepository->updateItem($id, $validated, $request->file('image'));
        return redirect()->route('products.index')->with('success', 'Product updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productRepository->destroyItem($id);
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
        $products = $this->productRepository->getListByIds($ids, ['subcategory.category', 'ratings']);
        if ($products->count() < 2) {
            return redirect()->route('products.index')->with('error', 'Products not found for comparison.');
        }
        return view('products.compare', compact('products'));
    }
}
