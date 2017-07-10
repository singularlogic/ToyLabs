<?php

namespace App\Http\Controllers;

use App\Design;
use App\Product;
use App\Prototype;

// use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'publicProducts', 'publicDesigns', 'publicPrototypes',
            'showProduct', 'showDesign', 'showPrototype',
        ]);
    }

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

    public function showProduct($id)
    {
        $product = Product::with(['designs', 'prototypes', 'comments.creator'])->find($id);
        // TODO: Return 404 if product does not exist

        $data = [
            'title'   => $product->title,
            'product' => $product,
            'model'   => [
                'type' => $product->type,
                'id'   => $product->id,
            ],
        ];

        return view('product.details', $data);
    }

    public function showDesign($id)
    {
        $design = Design::with(['prototypes', 'comments.creator'])->find($id);
        // TODO: Return 404 if design does not exist

        $data = [
            'title'  => $design->title,
            'design' => $design,
            'model'  => [
                'type' => $design->type,
                'id'   => $design->id,
            ],
        ];

        return view('product.design', $data);
    }

    public function showPrototype($id)
    {
        $prototype = Prototype::with(['comments.creator'])->find($id);
        // TODO: Return 404 if prototype does not exist

        $data = [
            'title'     => $prototype->title,
            'prototype' => $prototype,
            'model'     => [
                'type' => $prototype->type,
                'id'   => $prototype->id,
            ],
        ];

        return view('product.prototype', $data);
    }

}
