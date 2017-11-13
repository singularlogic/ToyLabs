<?php

namespace App\Http\Controllers;

use App\Design;
use App\Organization;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();

            return Design::where('is_public', true)
                ->orWhere(['owner_id' => $user->id, 'owner_type' => User::class])
                ->orWhere(['owner_id' => $user->organization, 'owner_type' => Organization::class])
                ->orderBy('updated_at', 'DESC')->get();
        }

        return Design::where('is_public', true)->get();
    }

    public function productDesigns(int $id)
    {
        $data = [
            'id'      => $id,
            'designs' => Design::where('product_id', $id)->orderBy('updated_at', 'DESC')->get(),
        ];

        return view('product.designs', $data);
    }

    public function show(Request $request, $id)
    {

    }

    public function create(Request $request, $id)
    {
        $data = [
            'title'      => 'Create Design',
            'product_id' => $id,
            'design'     => [
                'title'       => '',
                'description' => '',
                'is_public'   => 0,
            ],
        ];

        return view('design.create', $data);
    }

    public function doCreate(Request $request, $id)
    {
        $input   = $request->all();
        $user    = Auth::user();
        $product = Product::find($id);
        $files   = json_decode($input['files'], true);
        $images  = json_decode($input['images'], true);

        if ($this->canEdit($user, $product)) {
            $design = Design::create([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
                'product_id'  => $id,
            ]);

            foreach ($images as $image) {
                $path = storage_path('/app/' . $image['path']);
                $design->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
            }

            foreach ($files as $file) {
                $path = storage_path('/app/' . $file['path']);
                $design->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
            }

            \Session::flash('success', 'Design created successfully.');
            return redirect()->route('product.designs', ['id' => $id]);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    public function edit(Request $request, $id)
    {
        $design = Design::find($id);

        $data = [
            'title'      => 'Edit Design',
            'product_id' => $design->product_id,
            'design'     => $design,
        ];

        return view('design.create', $data);
    }

    public function doEdit(Request $request, $id)
    {
        $input   = $request->all();
        $design  = Design::find($id);
        $user    = Auth::user();
        $product = Product::find($design->product_id);
        $images  = json_decode($input['images'], true);
        $files   = json_decode($input['files'], true);

        if ($design && $this->canEdit($user, $product)) {
            Design::where('id', $id)->update([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
            ]);

            foreach ($images as $image) {
                $path = storage_path('/app/' . $image['path']);
                $design->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
            }

            foreach ($files as $file) {
                $path = storage_path('/app/' . $file['path']);
                $design->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
            }

            \Session::flash('success', 'Design updated successfully!');
            return redirect()->route('product.designs', ['id' => $design->product_id]);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    public function makePrototype(Request $request, $id, $design_id)
    {
        $user    = Auth::user();
        $product = Product::find($id);
        $design  = Design::find($design_id);

        if ($product && $design && $this->canEdit($user, $product)) {
            // Progress product status to the next step
            if ($product->status === 'design') {
                $product->status = 'prototype';
                $product->save();
            }

            // Create a new prototype based on the design
            $data = [
                'title'      => 'Create Prototype',
                'product_id' => $product->id,
                'design_id'  => $design->id,
                'prototype'  => [
                    'title'       => $design->title,
                    'description' => $design->description,
                    'is_public'   => 0,
                ],
            ];

            return view('prototype.create', $data);
        }

        \Session::flash('error', 'You are not permitted to edit this product!');
        return redirect()->route('product.designs', ['id' => $input['id']]);
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
