<?php

use App\GeographicalMarket;
use Illuminate\Database\Seeder;

class GeographicalMarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeographicalMarket::create(['name' => 'Regional']);
        GeographicalMarket::create(['name' => 'National']);
        GeographicalMarket::create(['name' => 'EU']);
        GeographicalMarket::create(['name' => 'International']);
    }
}
