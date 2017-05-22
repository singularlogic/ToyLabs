<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function guestview($id)
    {
        $data = [
            'title' => 'Lorem Ipsum',
        ];

        return view('project.guestview', $data);
    }
}
