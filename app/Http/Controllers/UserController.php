<?php

namespace App\Http\Controllers;

use App\Design;
use App\Models\ReportedComment;
use App\Product;
use App\Prototype;
use App\MarketAnalysisAnalysis;
use BrianFaust\Commentable\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLikes()
    {

    }

    public function like(Request $request, $type, $id)
    {
        $class = null;
        switch ($type) {
            case 'product':
                $class = Product::find($id);
                break;
            case 'design':
                $class = Design::find($id);
                break;
            case 'prototype':
                $class = Prototype::find($id);
                break;
            default:
                // No default
        }

        if ($class) {
            $class->like();
        } else {
            return response()->json([
                'error' => 'Resource not found',
            ], 404);
        }
    }

    public function unlike(Request $request, $type, $id)
    {
        $class = null;
        switch ($type) {
            case 'product':
                $class = Product::find($id);
                break;
            case 'design':
                $class = Design::find($id);
                break;
            case 'prototype':
                $class = Prototype::find($id);
                break;
            default:
                // No default
        }

        if ($class) {
            $class->unlike();
        } else {
            return response()->json([
                'error' => 'Resource not found',
            ], 404);
        }
    }

    public function postComment(Request $request)
    {
        $input = $request->all();
        $user  = Auth::user();
        $model = null;

        switch ($input['model']['type']) {
            case 'product':
                $model = Product::find($input['model']['id']);
                break;
            case 'design':
                $model = Design::find($input['model']['id']);
                break;
            case 'prototype':
                $model = Prototype::find($input['model']['id']);
                break;
            case 'analysis.'.ANALYSIS_TYPE::SOCIAL:
                $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $input['model']['id']], ['type', ANALYSIS_TYPE::SOCIAL]])->first();
                break;
            case 'analysis.'.ANALYSIS_TYPE::TREND:
                $model = MarketAnalysisAnalysis::where([['anlzer_analysis_id', $input['model']['id']], ['type', ANALYSIS_TYPE::TREND]])->first();
                break;
            default:
                break;
        }

        if ($model) {
            $parent  = Comment::find($input['parent_id']);
            $comment = $model->comment($input['comment'], $user, $parent);

            return Comment::with('creator')->find($comment->id);
        }
    }

    public function deleteComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->deleteComment($id);
    }

    public function reportComment(Request $request, int $id)
    {
        $user     = $request->user();
        $comment  = Comment::find($id);
        $reported = ReportedComment::where('comment_id', $id)->count();

        if ($comment && $reported === 0) {
            ReportedComment::create([
                'user_id'    => $user->id,
                'comment_id' => $id,
            ]);

            $comment->is_reported = true;
            $comment->save();
        }
    }
}
