<?php

namespace App\Http\Controllers;

use App\Product;
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

        $products = Product::where('owner_id', $user->id)->get();
        $data     = [
            'products' => $products,
        ];

        return view('dashboard', $data);
    }
}
