<?php

use App\OrganizationType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OrganizationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationType::create([
            'name'    => 'Toy Manufacturer',
            'role_id' => Role::where('name', 'manufacturer')->first()['id'],
        ]);
        OrganizationType::create([
            'name'    => 'FabLab',
            'role_id' => Role::where('name', 'fablab')->first()['id'],
        ]);
        OrganizationType::create([
            'name'    => 'Child Expert',
            'role_id' => Role::where('name', 'child_expert')->first()['id'],
        ]);
        OrganizationType::create([
            'name'    => 'Safety Expert',
            'role_id' => Role::where('name', 'safety_expert')->first()['id'],
        ]);
    }
}
