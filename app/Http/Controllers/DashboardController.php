<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->profile) {
            session()->flash('warning', 'Please edit your <a href="/profile/edit">profile</a> to use the full functionality of ToyLabs.');
        }

        $products = Product::with('owner')->where([
            'owner_id'   => $user->id,
            'owner_type' => User::class,
        ])->orWhere([
            'owner_id'   => $user->organization,
            'owner_type' => Organization::class,
        ])->orderBy('updated_at', 'DESC')->get();
        $data = [
            'products' => $products,
        ];

        return view('dashboard', $data);
    }
}
