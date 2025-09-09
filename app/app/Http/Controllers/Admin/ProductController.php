<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'stock' => 'nullable|integer|min:0',
            'is_made_to_order' => 'sometimes|boolean',
            'lead_time_hours' => 'required|integer|min:0',
            'active' => 'sometimes|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_made_to_order'] = (bool) ($data['is_made_to_order'] ?? false);
        $data['active'] = (bool) ($data['active'] ?? true);
        Product::create($data);
        return redirect()->route('products.index')->with('status', 'Creado');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'stock' => 'nullable|integer|min:0',
            'is_made_to_order' => 'sometimes|boolean',
            'lead_time_hours' => 'required|integer|min:0',
            'active' => 'sometimes|boolean',
        ]);
        $data['is_made_to_order'] = (bool) ($data['is_made_to_order'] ?? false);
        $data['active'] = (bool) ($data['active'] ?? true);
        $product->update($data);
        return redirect()->route('products.index')->with('status', 'Actualizado');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return back()->with('status', 'Eliminado');
    }
}

