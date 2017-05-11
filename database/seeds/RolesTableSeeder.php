<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manufacturer']);
        Role::create(['name' => 'fablab']);
        Role::create(['name' => 'child_expert']);
        Role::create(['name' => 'safety_expert']);
        Role::create(['name' => 'end_user']);

        Permission::create(['name' => 'edit.organization']);
    }
}
