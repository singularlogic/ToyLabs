<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RolesTableSeeder::class);
        // $this->call(OrganizationTypesSeeder::class);
        // $this->call(AddToyCategories::class);
        // $this->call(OrganizationTypesSeeder::class);
        $this->call(RegionalSeeder::class);
    }
}
