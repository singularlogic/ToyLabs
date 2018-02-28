<?php

namespace App\Http\Controllers;

use App\ARModel;
use App\Design;
use App\Prototype;
use Illuminate\Http\Request;

class AugmentedRealityController extends Controller
{
    public function index(Request $request, string $type, int $id)
    {
        $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        if (\Gate::denies('edit.product', $obj->product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'back'    => [
                'id'   => $obj->product_id,
                'type' => $type,
            ],
            'product' => $obj,
            'models'  => $obj->armodels,
        ];

        return view('ar.index', $data);
    }

    public function create(Request $request, string $type, int $id)
    {
        $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        if (\Gate::denies('edit.product', $obj->product)) {
            abort(401, 'Unauthorized access');
        }

    }

    public function doCreate(Request $request, string $type, int $id)
    {
        $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        if (\Gate::denies('edit.product', $obj->product)) {
            abort(401, 'Unauthorized access');
        }

    }

    public function show(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

    }

    public function update(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

    }

    public function doUpdate(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

    }

    public function delete(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

    }
}
