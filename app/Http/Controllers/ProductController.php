<?php

namespace App\Http\Controllers;

use App\Age;
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
        $product = Product::with(['designs', 'prototypes', 'comments.creator', 'media'])->find($id);
        if (!$product->is_public && \Gate::denies('view.product', $product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'          => $product->title,
            'product'        => $product,
            'model'          => [
                'type' => $product->type,
                'id'   => $product->id,
            ],
            'files'          => $product->getMedia('files'),
            'isCollaborator' => $this->isCollaborator(Auth::user(), $product, Product::class),
        ];

        return view('product.details', $data);
    }

    public function showDesign($id)
    {
        $design = Design::with(['prototypes', 'comments.creator'])->find($id);
        if (!$design->is_public && \Gate::denies('view.product', $design->product) && \Gate::denies('collaborate.design', $design)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'          => $design->title,
            'design'         => $design,
            'model'          => [
                'type' => $design->type,
                'id'   => $design->id,
            ],
            'files'          => $design->getMedia('files'),
            'isCollaborator' => $this->isCollaborator(Auth::user(), $design, Design::class),
        ];

        return view('product.design', $data);
    }

    public function showPrototype($id)
    {
        $prototype = Prototype::with(['comments.creator'])->find($id);
        if (!$prototype->is_public && \Gate::denies('view.product', $prototype->product) && \Gate::denies('collaborate.prototype', $prototype)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'          => $prototype->title,
            'prototype'      => $prototype,
            'model'          => [
                'type' => $prototype->type,
                'id'   => $prototype->id,
            ],
            'files'          => $prototype->getMedia('files'),
            'isCollaborator' => $this->isCollaborator(Auth::user(), $prototype, Prototype::class),
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
            'ages'          => Age::orderBy('value', 'ASC')->get(),
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
        $input  = $request->all();
        $files  = json_decode($input['files'], true);
        $images = json_decode($input['images'], true);

        $product = Product::create([
            'title'       => $input['title'],
            'description' => $input['description'],
            'min_age'     => isset($input['min_age']) ? $input['min_age'] : null,
            'max_age'     => isset($input['max_age']) ? $input['max_age'] : null,
            'is_public'   => $input['is_public'],
            'category_id' => isset($input['category_id']) ? $input['category_id'] : null,
            'owner_id'    => $input['owner_id'],
            'owner_type'  => $input['owner_type'],
        ]);

        foreach ($images as $image) {
            $path = storage_path('/app/' . $image['path']);
            $product->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
        }

        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $product->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        return redirect('dashboard')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('owner')->find($id);
        if (\Gate::denies('edit.product', $product)) {
            abort(401, 'Unauthorized access');
        };

        if (!$product) {
            return redirect('dashboard')->with('error', 'Product not found!');
        }

        $user = Auth::user();
        $data = [
            'title'         => 'Edit Product',
            'user'          => $user,
            'organizations' => $user->organizations,
            'categories'    => ToyCategory::all(),
            'ages'          => Age::orderBy('value', 'ASC')->get(),
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
        $files   = json_decode($input['files'], true);
        $images  = json_decode($input['images'], true);

        if ($product && \Gate::allows('edit.product', $product)) {
            Product::where('id', $id)->update([
                'title'       => $input['title'],
                'description' => $input['description'],
                'min_age'     => isset($input['min_age']) ? $input['min_age'] : null,
                'max_age'     => isset($input['max_age']) ? $input['max_age'] : null,
                'is_public'   => isset($input['is_public']) ? $input['is_public'] : false,
                'category_id' => isset($input['category_id']) ? $input['category_id'] : null,
                'owner_id'    => $input['owner_id'],
                'owner_type'  => $input['owner_type'],
                'status'      => $input['status'] ?: $product['status'],
            ]);

            foreach ($images as $image) {
                $path = storage_path('/app/' . $image['path']);
                $product->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
            }

            foreach ($files as $file) {
                $path = storage_path('/app/' . $file['path']);
                $product->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
            }

            if ($input['status']) {
                return redirect()->route('product.analysis', ['id' => $id])->with('success', 'Product updated successfully!');
            }
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

    protected function isCollaborator($user, $object, $type)
    {
        if (!$user) {
            return false;
        }

        if ($type === Product::class) {
            return $this->canEdit($user, $object);
        } else {
            return $this->canEdit($user, $object->product);
        }
    }
}
