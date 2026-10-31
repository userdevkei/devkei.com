<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', ['products' => Product::active()->get()]);
    }
}
