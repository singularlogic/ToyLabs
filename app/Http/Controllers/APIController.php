<?php

namespace App\Http\Controllers;

use App\City;
use App\State;

class APIController extends Controller
{
    public function countryStates(int $id)
    {
        return State::where('country_id', $id)->orderBy('name', 'ASC')->get();
    }

    public function stateCities(int $id)
    {
        return City::where('state_id', $id)->orderBy('name', 'ASC')->get();
    }
}
