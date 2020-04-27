<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Note;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'coefficient' => $faker->numberBetween(2,6)/2,
        'date' => $faker->dateTimeBetween('now', '6 months')->format('Y-m-d'),
        'description' => $faker->sentence()
    ];
});
