<?php

namespace App\Http\Controllers;

use App\Design;
use App\Product;
use App\Prototype;

// use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function publicProducts()
    {
        $products = Product::where('is_public', true)->get();

        return $products;
    }

    public function publicDesigns()
    {
        $designs = Design::where('is_public', true)->get();

        return $designs;
    }

    public function publicPrototypes()
    {
        $prototypes = Prototype::where('is_public', true)->get();

        return $prototypes;
    }
}
