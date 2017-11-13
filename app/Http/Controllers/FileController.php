<?php

namespace App\Http\Controllers;

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
            abort(404);
        }

        return response()->file($media->getPath(), ['Content-Type' => $media->mime_type]);
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
        if ($media) {
            $media->delete();
            return response()->json([])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json([])->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
