<?php

namespace App\Http\Controllers;

use App\Design;
use App\Product;
use App\Prototype;
use App\ToyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function create()
    {
        $user = \Auth::user();

        $data = [
            'title'         => 'New Product',
            'user'          => $user,
            'organizations' => $user->organizations,
            'categories'    => ToyCategory::all(),
            'product'       => [
                'title'       => '',
                'description' => '',
                'is_public'   => 0,
                'status'      => 'concept',
                'owner'       => $user,
                'owner_id'    => $user->id,
                'owner_type'  => 'App\\User',
                'ages'        => '',
                'category_id' => -1,
            ],
        ];

        return view('product.create', $data);
    }

    public function doCreate(Request $request)
    {
        $input = $request->all();

        Product::create([
            'title'       => $input['title'],
            'description' => $input['description'],
            'ages'        => $input['ages'],
            'is_public'   => $input['is_public'],
            'category_id' => $input['category_id'],
            'owner_id'    => $input['owner_id'],
            'owner_type'  => $input['owner_type'],
        ]);

        return redirect('dashboard')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('owner')->find($id);

        if (!$product) {
            return redirect('dashboard')->with('error', 'Product not found!');
        }

        $user = Auth::user();
        $data = [
            'title'         => 'Edit Product',
            'user'          => $user,
            'organizations' => $user->organizations,
            'categories'    => ToyCategory::all(),
            'product'       => $product,
        ];

        if ($this->canEdit($user, $product)) {
            return view('product.create', $data);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    public function doEdit(Request $request, $id)
    {
        $input   = $request->all();
        $product = Product::find($id);
        $user    = Auth::user();

        if ($product && $this->canEdit($user, $product)) {
            Product::where('id', $id)->update([
                'title'       => $input['title'],
                'description' => $input['description'],
                'ages'        => $input['ages'],
                'is_public'   => $input['is_public'],
                'category_id' => $input['category_id'],
                'owner_id'    => $input['owner_id'],
                'owner_type'  => $input['owner_type'],
            ]);

            return redirect('dashboard')->with('success', 'Product updated successfully!');
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    protected function canEdit($user, $product)
    {
        if (is_a($product->owner, 'App\User') && $product->owner->id === $user->id) {
            return true;
        } else if (is_a($product->owner, 'App\Organization') && $user->organizations->where('id', $product->owner->id)) {
            return true;
        }

        return false;
    }
}
