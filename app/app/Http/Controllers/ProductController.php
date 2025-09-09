<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->where('active', true)->get();
        return view('shop.index', compact('products'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('shop.show', compact('product'));
    }
}

