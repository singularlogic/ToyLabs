<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'description' => $faker->paragraphs($nb = 5, $asText = true),
        'is_public'   => $faker->boolean($chanceOfGettingTrue = 10),
        'owner_id'    => 1,
        'owner_type'  => App\User::class,
        'ages'        => '3+',
        'status'      => 'concept',
    ];
});

$factory->define(App\Design::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'description' => $faker->paragraphs($nb = 3, $asText = true),
        'is_public'   => $faker->boolean($chanceOfGettingTrue = 25),
    ];
});

$factory->define(App\Prototype::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'description' => $faker->paragraphs($nb = 3, $asText = true),
        'is_public'   => $faker->boolean($chanceOfGettingTrue = 50),
    ];
});
