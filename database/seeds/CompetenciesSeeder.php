<?php

use App\Competency;
use Illuminate\Database\Seeder;

class CompetenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Competency::create(['name' => '3D Modelling']);
        Competency::create(['name' => '3D Printing']);
        Competency::create(['name' => '3D Scanning']);
        Competency::create(['name' => 'CAE/FEM and Structural Analysis Simulation']);
        Competency::create(['name' => 'Circuit Production']);
        Competency::create(['name' => 'CNC Milling']);
        Competency::create(['name' => 'Inkjet Printing']);
        Competency::create(['name' => 'Knitting Machine']);
        Competency::create(['name' => 'Laser Cutting']);
        Competency::create(['name' => 'Mould Casting']);
        Competency::create(['name' => 'Plastic Transformation']);
        Competency::create(['name' => 'Sewing Machine']);
        Competency::create(['name' => 'Soldering Station']);
        Competency::create(['name' => 'Vacuum Forming']);
        Competency::create(['name' => 'Vinyl Cutting']);
    }
}
