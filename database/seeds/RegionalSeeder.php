<?php

use App\City;
use App\Country;
use App\State;
use Illuminate\Database\Seeder;

class RegionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Countries
        $json = File::get('database/seeds/data/countries.json');
        $data = json_decode($json, true);
        foreach ($data['countries'] as $country) {
            Country::create([
                'id'       => (int) $country['id'],
                'sortcode' => $country['sortname'],
                'name'     => $country['name'],
            ]);
        }

        // States
        $json = File::get('database/seeds/data/states.json');
        $data = json_decode($json, true);
        foreach ($data['states'] as $state) {
            State::create([
                'id'         => (int) $state['id'],
                'name'       => $state['name'],
                'country_id' => (int) $state['country_id'],
            ]);
        }

        // Cities
        $json = File::get('database/seeds/data/cities.json');
        $data = json_decode($json, true);
        foreach ($data['cities'] as $city) {
            City::create([
                'name'     => $city['name'],
                'state_id' => (int) $city['state_id'],
            ]);
        }
    }
}
