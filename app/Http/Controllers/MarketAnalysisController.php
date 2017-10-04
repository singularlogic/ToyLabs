<?php

namespace App\Http\Controllers;

class MarketAnalysisController extends Controller
{
    public function index(int $id)
    {
        $data = [];

        return view('product.market', $data);
    }
}
