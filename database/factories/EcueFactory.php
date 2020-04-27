<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ecue;
use Faker\Generator as Faker;

$factory->define(Ecue::class, function (Faker $faker) {
    return [
        'code' => 'ECUE' . $faker->unique()->bothify('???###'),
        'label' => $faker->unique()->sentence(2)
    ];
});
