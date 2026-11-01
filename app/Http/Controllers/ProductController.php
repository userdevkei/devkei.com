<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Product::active()->get()]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('products.show', ['product' => $product]);
    }
}
