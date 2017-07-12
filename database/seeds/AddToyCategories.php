<?php

use App\ToyCategory;
use Illuminate\Database\Seeder;

class AddToyCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ToyCategory::create([
            'title'       => 'Activity Toys',
            'description' => 'Swings, Slides, Gym Set, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Aquatic Toys',
            'description' => 'Inflatable PVC toys, bathing toys, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Arts, Crafts Materials And Related Articles',
            'description' => 'Crayons, chalks, pens, scissors, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Audio/Visual Equipment',
            'description' => 'Music Boxes, Simple Computers, CD Players, etc.',
        ]);

        ToyCategory::create([
            'title' => 'Books with Play Value',
        ]);

        ToyCategory::create([
            'title' => 'Construction Toys and Puzzles',
        ]);

        ToyCategory::create([
            'title' => 'Costumes, Disguises and Masks',
        ]);

        ToyCategory::create([
            'title' => 'Colls and Soft-filled Toys',
        ]);

        ToyCategory::create([
            'title'       => 'Experimental Sets',
            'description' => 'Electrical, Photographic Sets, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Functional Toys',
            'description' => 'Toolboxes, Microscopes, Typewriters, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Game Sets',
            'description' => 'Picture Dominoes, Simple Matching Games, Board Games, Bingo, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Mechanical and/or Electrical Driven Vehicles',
            'description' => 'Not intended to bear the mass of a child: trains that make noise, pull-back vehicles, track racing sets, actin figure vehicles, etc.)',
        ]);

        ToyCategory::create([
            'title'       => 'Play Scenes and Constructed Models',
            'description' => 'Farms, Garages, Dollhouses, Detailed and Realistic Cars, Puppet Theatres, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Projectile Toys with Launching Device',
            'description' => 'Roys that eject balls, bows and arrows, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Push-along Toys and Walking Aids',
            'description' => 'Animals on wheels, boats, cars, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Role-playing Toys',
            'description' => 'For imitation of adult activities',
        ]);

        ToyCategory::create([
            'title' => 'Sand-Water Toys',
        ]);

        ToyCategory::create([
            'title'       => 'Skill Development Toys',
            'description' => 'Stacking blocks, shape sorters, sewing kits, modelling sets, etc.',
        ]);

        ToyCategory::create([
            'title' => 'Toy Cosmetics',
        ]);

        ToyCategory::create([
            'title' => 'Toy Musical Instruments',
        ]);

        ToyCategory::create([
            'title' => 'Toy Sports Equipment and Balls',
        ]);

        ToyCategory::create([
            'title'       => 'Toys for Babies to Look at, Grasping and/or Squeezing',
            'description' => 'Activity centres, teethers, cribs and play gyms, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Toys Intended to Bear the Mass of a Child',
            'description' => 'Ride-ons, vehicles with/without battery, etc.',
        ]);

        ToyCategory::create([
            'title'       => 'Toys Intended to be Entered by a Child',
            'description' => 'Soft houses or tents, rigid playhouses, play tunnels, etc.',
        ]);

        ToyCategory::create([
            'title' => 'Other',
        ]);

    }
}
