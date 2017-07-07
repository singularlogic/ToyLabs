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
        $products = factory(App\Product::class, 20)
            ->create()
            ->each(function ($u) {
                $u->designs()->save(factory(App\Design::class)->make());
                $u->prototypes()->save(factory(App\Prototype::class)->make());
            });
    }
}
