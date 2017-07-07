<?php

namespace App\Http\Controllers;

use App\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_public', true)->get();

        $data = [
            'products' => $products,
        ];

        // $data = [
        //     "products" => [
        //         [
        //             "id"          => 1,
        //             "type"        => "product",
        //             "title"       => "Product Title",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 3,
        //             "likes"       => 8,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //         [
        //             "id"          => 2,
        //             "type"        => "prototype",
        //             "title"       => "Prototype Title",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 8,
        //             "likes"       => 2,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //         [
        //             "id"          => 3,
        //             "type"        => "design",
        //             "title"       => "Design Title",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 13,
        //             "likes"       => 0,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //         [
        //             "id"          => 4,
        //             "type"        => "design",
        //             "title"       => "Design Title 2",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 2,
        //             "likes"       => 0,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //         [
        //             "id"          => 5,
        //             "type"        => "design",
        //             "title"       => "Design Title 3",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 1,
        //             "likes"       => 1,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //         [
        //             "id"          => 6,
        //             "type"        => "prototype",
        //             "title"       => "Prototype Title 4",
        //             "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //             "comments"    => 0,
        //             "likes"       => 0,
        //             "image"       => "/images/placeholder.jpg",
        //         ],
        //     ],
        // ];

        return view('home', $data);
    }
}
