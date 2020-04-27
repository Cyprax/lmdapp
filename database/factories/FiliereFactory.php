<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Filiere;
use Faker\Generator as Faker;

$factory->define(Filiere::class, function (Faker $faker) {
    return [
        'label' => $faker->unique()->word,
    ];
});
