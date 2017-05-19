<?php

namespace App\Http\Controllers;

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

        switch ($user->role) {
            case 'manufacturer':
            case 'fablab':
            case 'child_expert':
            case 'safety_expert':
            default: // end_user
                return view('dashboard');
        }
    }
}
