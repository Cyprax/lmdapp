<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Classe;
use Faker\Generator as Faker;

$factory->define(Classe::class, function (Faker $faker) {
    return [
        'label' => $faker->unique()->word,
    ];
});
