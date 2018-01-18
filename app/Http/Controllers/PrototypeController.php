<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Product;
use App\Prototype;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrototypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        if ($request->user()) {
            $user = $request->user();

            return Prototype::where('is_public', true)
                ->orWhere(['owner_id' => $user->id, 'owner_type' => User::class])
                ->orWhere(['owner_id' => $user->organization, 'owner_type' => Organization::class])
                ->orderBy('updated_at', 'DESC')->get();
        }

        return Prototype::where('is_public', true)->get();
    }

    public function productPrototypes(int $id)
    {
        $product = Product::find($id);
        if (\Gate::denies('edit.product', $product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'id'         => $id,
            'prototypes' => $product->prototypes()->orderBy('updated_at', 'DESC')->get(),
        ];

        return view('product.prototypes', $data);
    }

    public function show($id)
    {

    }

    public function create(Request $request, $id)
    {
        $product = Product::find($id);
        if (\Gate::denies('edit.product', $product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'      => 'Create Prototype',
            'product_id' => $id,
            'prototype'  => [
                'title'       => '',
                'description' => '',
                'is_public'   => 0,
            ],
        ];

        return view('prototype.create', $data);
    }

    public function doCreate(Request $request, $id)
    {
        $input   = $request->all();
        $user    = Auth::user();
        $product = Product::find($id);
        $files   = json_decode($input['files'], true);
        $images  = json_decode($input['images'], true);

        if (\Gate::allows('edit.product', $product)) {
            $prototype = Prototype::create([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
                'product_id'  => $id,
                'design_id'   => $input['design_id'],
            ]);

            foreach ($images as $image) {
                $path = storage_path('/app/' . $image['path']);
                $prototype->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
            }

            foreach ($files as $file) {
                $path = storage_path('/app/' . $file['path']);
                $prototype->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
            }

            \Session::flash('success', 'Prototype created successfully.');
            return redirect()->route('product.prototypes', ['id' => $id]);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    public function edit(Request $request, $id)
    {
        $prototype = Prototype::find($id);
        if (\Gate::denies('edit.product', $prototype->product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'      => 'Edit Prototype',
            'product_id' => $prototype->product_id,
            'design_id'  => $prototype->design ? $prototype->design->id : null,
            'prototype'  => $prototype,
        ];

        return view('prototype.create', $data);
    }

    public function doEdit(Request $request, $id)
    {
        $input     = $request->all();
        $prototype = Prototype::find($id);
        $user      = Auth::user();
        $product   = Product::find($prototype->product_id);
        $images    = json_decode($input['images'], true);
        $files     = json_decode($input['files'], true);

        if ($prototype && \Gate::allows('edit.product', $product)) {
            Prototype::where('id', $id)->update([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
            ]);

            foreach ($images as $image) {
                $path = storage_path('/app/' . $image['path']);
                $prototype->addMedia($path)->usingName($image['name'])->toMediaCollection('images');
            }

            foreach ($files as $file) {
                $path = storage_path('/app/' . $file['path']);
                $prototype->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
            }

            \Session::flash('success', 'Prototype updated successfully!');
            return redirect()->route('product.prototypes', ['id' => $prototype->product_id]);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }
}
