<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Middleware\IsAdmin;
use Illuminate\Routing\Attributes\Middleware;
use App\Http\Requests\SubcategoryRequest;

#[Middleware('is_admin')]
class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('name')->get();
        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubcategoryRequest $request)
    {
        $validated = $request->validated();
        Subcategory::create($validated);
        return redirect()->route('subcategories.index')->with('success', 'Subcategory added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubcategoryRequest $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $validated = $request->validated();
        $subcategory->update($validated);
        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Підкатегорію видалено!');
    }
}
