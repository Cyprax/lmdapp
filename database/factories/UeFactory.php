<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ue;
use Faker\Generator as Faker;

$factory->define(Ue::class, function (Faker $faker) {
    return [
        'code' => 'UE' . $faker->unique()->bothify('???##'),
        'label' => $faker->unique()->sentence(2)
    ];
});
