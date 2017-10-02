<?php

use App\Age;
use Illuminate\Database\Seeder;

class AgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Age::create(['name' => '0', 'value' => 0]);
        Age::create(['name' => '1 m.o.', 'value' => 1]);
        Age::create(['name' => '2 m.o.', 'value' => 2]);
        Age::create(['name' => '3 m.o.', 'value' => 3]);
        Age::create(['name' => '4 m.o.', 'value' => 4]);
        Age::create(['name' => '5 m.o.', 'value' => 5]);
        Age::create(['name' => '6 m.o.', 'value' => 6]);
        Age::create(['name' => '7 m.o.', 'value' => 7]);
        Age::create(['name' => '8 m.o.', 'value' => 8]);
        Age::create(['name' => '9 m.o.', 'value' => 9]);
        Age::create(['name' => '10 m.o.', 'value' => 10]);
        Age::create(['name' => '11 m.o.', 'value' => 11]);
        Age::create(['name' => '12 m.o.', 'value' => 12]);
        Age::create(['name' => '18 m.o.', 'value' => 18]);
        Age::create(['name' => '2 y.o.', 'value' => 24]);
        Age::create(['name' => '3 y.o.', 'value' => 36]);
        Age::create(['name' => '4 y.o.', 'value' => 48]);
        Age::create(['name' => '5 y.o.', 'value' => 60]);
        Age::create(['name' => '6 y.o.', 'value' => 72]);
        Age::create(['name' => '7 y.o.', 'value' => 84]);
        Age::create(['name' => '8 y.o.', 'value' => 96]);
        Age::create(['name' => '9 y.o.', 'value' => 108]);
        Age::create(['name' => '10 y.o.', 'value' => 120]);
        Age::create(['name' => '11 y.o.', 'value' => 132]);
        Age::create(['name' => '12 y.o.', 'value' => 144]);
        Age::create(['name' => '13 y.o.', 'value' => 156]);
        Age::create(['name' => '14 y.o.', 'value' => 168]);
        Age::create(['name' => '14 y.o.+', 'value' => 180]);
    }
}
