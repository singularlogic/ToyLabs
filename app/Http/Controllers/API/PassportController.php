<?php

namespace App\Http\Controllers\API;

use App\ARModel;
use App\ARQuestionAnswer;
use App\Design;
use App\Http\Controllers\Controller;
use App\Organization;
use App\Product;
use App\Prototype;
use App\User;
use Illuminate\Http\Request;
use ZipArchive;

class PassportController extends Controller
{
    public function login()
    {
        if (\Auth::attempt([
            'email'    => request('email'),
            'password' => request('password'),
        ])) {
            $user             = \Auth::user();
            $success['token'] = $user->createToken('Toylabs')->accessToken;
            return response()->json([
                'success' => $success,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => 'Logged out',
        ], 200);
    }

    public function getDetails()
    {
        $user = \Auth::user();
        return response()->json([
            'success' => $user,
        ], 200);
    }

    public function getModels(Request $request)
    {
        $user = $request->user();

        // Get models from public Products, Designs and Prototypes
        $products   = Product::where('is_public', true)->get();
        $designs    = Design::where('is_public', true)->get();
        $prototypes = Prototype::where('is_public', true)->get();

        // Get models from your own (or your organization's) products
        $myProducts = Product::with('owner')->where([
            'owner_id'   => $user->id,
            'owner_type' => User::class,
        ])->orWhere([
            'owner_id'   => $user->organization,
            'owner_type' => Organization::class,
        ])->get();

        $products = $products->merge($prototypes);
        $products = $products->merge($designs);
        $products = $products->merge($myProducts);

        // Retrieve my collaborations
        $orgs   = $user->myOrganizations;
        $active = collect();
        foreach ($orgs as $org) {
            $active = $active->union($org->activeCollaborations);
        }

        // Add the Designs/Prototypes I am collaborating for to the list
        foreach ($active as $collaboration) {
            $products->add($collaboration->collaboratable);
        }

        // Retrieve the AR models from all the Products/Designs/Prototypes
        $models = collect();
        foreach ($products as $product) {
            $models = $models->merge($product->armodels);
        }

        // Return a list of unique models
        return response()->json($models->unique(), 200);
    }

    public function downloadModel(Request $request, int $id)
    {
        $user   = $request->user();
        $model  = ARModel::findOrFail($id);
        $parent = $model->parent;

        if (!$parent->is_public && \Gate::denies('view.product', $parent->product) && \Gate::denies('collaborate.' . $parent->type, $design)) {
            return abort(401);
        }

        $path = public_path("tmp/$model->id.zip");
        $zip  = new ZipArchive;

        // If file does not exist, create it
        if (!file_exists($path)) {
            if ($zip->open($path, ZipArchive::CREATE) === true) {
                foreach ($model->getMedia('files') as $file) {
                    $zip->addFile($file->getPath(), $file->name);
                }
                $zip->close();
            }
        }

        return response()->download($path, "$model->id.zip", [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    public function getQuestions(Request $request, int $id)
    {
        $user   = $request->user();
        $model  = ARModel::findOrFail($id);
        $parent = $model->parent;

        if (!$parent->is_public && \Gate::denies('view.product', $parent->product) && \Gate::denies('collaborate.' . $parent->type, $design)) {
            return abort(401);
        }

        return response()->json([
            'questions' => $model->questions()->get(['id', 'text']),
        ], 200);
    }

    public function postFeedback(Request $request, int $id)
    {
        $user   = $request->user();
        $model  = ARModel::findOrFail($id);
        $parent = $model->parent;

        if (!$parent->is_public && \Gate::denies('view.product', $parent->product) && \Gate::denies('collaborate.' . $parent->type, $design)) {
            return abort(401);
        }

        $input = $request->all();

        // Validate
        $ids        = implode(',', $model->questions->pluck(['id'])->toArray());
        $validation = \Validator::make($input, [
            'questions.*.id'    => 'required|in:' . $ids,
            'questions.*.value' => 'required|integer|min:1|max:5',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->errors(),
            ], 400);
        }

        // Save feedback
        foreach ($input['questions'] as $question) {
            ARQuestionAnswer::create([
                'ar_question_id' => $question['id'],
                'user_id'        => $input['user_id'],
                'value'          => $question['value'],
            ]);
        }

        $model->comment([
            'body' => $input['comment'],
        ], $user);

        return response()->json([
            'success' => 'Feedback accepted',
        ], 200);
    }
}
