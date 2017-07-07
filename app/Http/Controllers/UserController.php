<?php

namespace App\Http\Controllers;

use App\Design;
use App\Product;
use App\Prototype;
use Illuminate\Http\Request;

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

}
