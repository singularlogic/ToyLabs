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
        $data = [
            'id'         => $id,
            'prototypes' => Prototype::where('product_id', $id)->orderBy('updated_at', 'DESC')->get(),
        ];

        return view('product.prototypes', $data);
    }

    public function show($id)
    {

    }

    public function create(Request $request, $id)
    {
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

        if ($this->canEdit($user, $product)) {
            $prototype = Prototype::create([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
                'product_id'  => $id,
                'design_id'   => $input['design_id'],
            ]);

            \Session::flash('success', 'Prototype created successfully.');
            return redirect()->route('product.prototypes', ['id' => $id]);
        }

        return redirect('dashboard')->with('error', 'You are not permitted to edit this product!');
    }

    public function edit(Request $request, $id)
    {
        $prototype = Prototype::find($id);

        $data = [
            'title'      => 'Edit Prototype',
            'product_id' => $prototype->product_id,
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

        if ($prototype && $this->canEdit($user, $product)) {
            Prototype::where('id', $id)->update([
                'title'       => $input['title'],
                'description' => $input['description'],
                'is_public'   => $input['is_public'],
            ]);

            \Session::flash('success', 'Prototype updated successfully!');
            return redirect()->route('product.prototypes', ['id' => $prototype->product_id]);
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
