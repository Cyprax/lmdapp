<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Progression;
use Faker\Generator as Faker;

$factory->define(Progression::class, function (Faker $faker) {
    return [
        'data' => $faker->randomHtml(2,3)
    ];
});
