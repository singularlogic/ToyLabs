<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

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

    public function getDetails()
    {
        $user = \Auth::user();
        return response()->json([
            'success' => $user,
        ], 200);
    }
}
