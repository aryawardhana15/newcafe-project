<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('manage_categories');
        
        $categories = MenuCategory::withCount('products')->get();
        return view('menu.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('manage_categories');
        return view('menu.categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage_categories');
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:menu_categories',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        MenuCategory::create($validatedData);

        return redirect()->route('menu-categories.index')
            ->with('success', 'Kategori menu berhasil ditambahkan!');
    }

    public function edit(MenuCategory $category)
    {
        $this->authorize('manage_categories');
        return view('menu.categories.edit', compact('category'));
    }

    public function update(Request $request, MenuCategory $category)
    {
        $this->authorize('manage_categories');
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:menu_categories,name,' . $category->id,
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $category->update($validatedData);

        return redirect()->route('menu-categories.index')
            ->with('success', 'Kategori menu berhasil diperbarui!');
    }

    public function destroy(MenuCategory $category)
    {
        $this->authorize('manage_categories');
        
        if($category->products()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk!');
        }

        $category->delete();
        return back()->with('success', 'Kategori menu berhasil dihapus!');
    }
} 