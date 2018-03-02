<?php

namespace App\Http\Controllers;

use App\ARModel;
use App\ARQuestion;
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

        $data = [
            'title' => 'Create AR Model',
            'back'  => [
                'id'   => $id,
                'type' => $type,
            ],
            'model' => [
                'title'       => '',
                'description' => '',
                'questions'   => [
                    ['text' => 'Design'],
                    ['text' => 'Features'],
                    ['text' => 'Novelity'],
                ],
                'parent_id'   => $id,
                'parent_type' => $type,
            ],
        ];

        return view('ar.create', $data);
    }

    public function doCreate(Request $request, string $type, int $id)
    {
        $obj = $type === 'design' ? Design::findOrFail($id) : Prototype::findOrFail($id);
        if (\Gate::denies('edit.product', $obj->product)) {
            abort(401, 'Unauthorized access');
        }

        $input     = $request->all();
        $files     = json_decode($input['files'], true);
        $questions = json_decode($input['questions'], true);
        $model     = ARModel::create([
            'title'       => $input['title'],
            'description' => isset($input['description']) ? $input['description'] : null,
            'parent_id'   => $id,
            'parent_type' => $type === 'design' ? Design::class : Prototype::class,
        ]);
        $model->questions()->createMany($questions);

        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $model->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        \Session::flash('success', 'AR Model created successfully.');
        return redirect()->route('ar-models', ['type' => $type, 'id' => $id]);
    }

    public function show(Request $request, int $id)
    {
        $model = ARModel::with(['comments.creator', 'questions'])->findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title'  => $model->title,
            'model'  => $model,
            'back'   => [
                'id'   => $model->parent->id,
                'type' => $model->parent->type,
            ],
            '_model' => [
                'id'   => $model->id,
                'type' => ARModel::class,
            ],
        ];

        return view('ar.show', $data);
    }

    public function update(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

        $data = [
            'title' => 'Update AR Model',
            'back'  => [
                'id'   => $model->parent->id,
                'type' => $model->parent->type,
            ],
            'model' => $model->append('files'),
        ];

        return view('ar.create', $data);
    }

    public function doUpdate(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

        $input     = $request->all();
        $files     = json_decode($input['files'], true);
        $questions = json_decode($input['questions'], true);

        $model->title       = $input['title'];
        $model->description = $input['description'];
        $model->save();

        foreach ($questions as $question) {
            ARQuestion::where('id', $question['id'])->update([
                'text' => $question['text'],
            ]);
        }

        foreach ($files as $file) {
            $path = storage_path('/app/' . $file['path']);
            $model->addMedia($path)->usingName($file['name'])->toMediaCollection('files');
        }

        \Session::flash('success', 'AR Model updated successfully.');
        return redirect()->route('ar-models', ['type' => $model->parent->type, 'id' => $model->parent->id]);
    }

    public function delete(Request $request, int $id)
    {
        $model = ARModel::findOrFail($id);
        if (\Gate::denies('edit.product', $model->parent->product)) {
            abort(401, 'Unauthorized access');
        }

        $model->delete();
    }
}
