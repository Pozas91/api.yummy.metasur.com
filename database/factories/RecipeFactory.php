<?php

/** @var Factory $factory */

use App\Models\Recipe;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Recipe::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->realText(),
        'duration' => $faker->numberBetween(1, 200000),
        'image' => $faker->imageUrl(640, 480, 'food'),
        'ingredients' => $faker->realText(),
        'rations' => $faker->numberBetween(1, 10),
        'steps' => $faker->realText(),
        'user_id' => 1
    ];
});
