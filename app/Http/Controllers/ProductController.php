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
        $product = Product::with(['designs', 'prototypes', 'comments'])->find($id);

        $comments = [
            [
                'id'         => 1,
                'author'     => [
                    'name'  => 'Elliot Jones',
                    'image' => '/images/avatar/small/elliot.jpg',
                ],
                'body'       => 'Lorem Ipsum',
                'created_at' => '2017-07-03 23:35',
                'comments'   => [
                    [
                        'id'         => 2,
                        'author'     => [
                            'name'  => 'Jenny Doe',
                            'image' => '/images/avatar/small/jenny.jpg',
                        ],
                        'body'       => 'Lorem Ipsum indeed',
                        'created_at' => '2017-07-05 09:28',
                        'comments'   => [],
                    ],
                ],
            ],
        ];

        $data = [
            'title'   => $product->title,
            'product' => $product,
        ];

        return view('product.details', $data);
    }
}
