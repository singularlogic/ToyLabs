<?php

namespace App\Http\Controllers;

use App\ARModel;
use App\Design;
use App\Product;
use App\Prototype;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Media;

class FileController extends Controller
{
    public function get($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([])->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $model = $media->model;
        if (!$model->is_public) {
            if (is_a($model, Product::class) && \Gate::denies('view.product', $model)) {
                return response()->json([])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }

            if (is_a($model, Design::class) && \Gate::denies('view.product', $model->product) && \Gate::denies('collaborate.design', $model)) {
                return response()->json([])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }

            if (is_a($model, Prototype::class) && \Gate::denies('view.product', $model->product) && \Gate::denies('collaborate.prototype', $model)) {
                return response()->json([])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
        }

        return response()->file($media->getPath(), [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $media->name . '"',
            'Content-Length'      => $media->size,
        ]);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->image->store('images');
            return response()->json(['path' => $path])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }

        if ($request->hasFile('file')) {
            $path = $request->file->store('files');
            return response()->json(['path' => $path])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }

        return response()->json(['error' => 'File not found'])->setStatusCode(Response::HTTP_BAD_REQUEST, Response::$statusTexts[Response::HTTP_BAD_REQUEST]);
    }

    public function delete(Request $request)
    {
        $input = $request->all();

        if ($input['path']) {
            Storage::delete($input['path']);
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json([])->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    public function remove(Request $request)
    {
        $input = $request->all();
        $media = Media::find($input['id']);
        if (\Gate::denies('edit.media', $media)) {
            return response()->json([])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if ($media) {
            if ($media->model_type === ARModel::class) {
                unlink(public_path("tmp/$media->model_id.zip"));
            }
            $media->delete();
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json([])->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
