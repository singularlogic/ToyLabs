<?php

namespace App\Http\Controllers;

use App\Design;
use App\Product;
use App\Prototype;

class HomeController extends Controller
{
    public function index()
    {
        $products   = Product::where('is_public', true)->get();
        $designs    = Design::where('is_public', true)->get();
        $prototypes = Prototype::where('is_public', true)->get();
        $products   = $products->merge($designs);
        $products   = $products->merge($prototypes);
        $products   = $products->shuffle();

        $data = [
            'products' => $products,
        ];

        return view('home', $data);
    }
}
