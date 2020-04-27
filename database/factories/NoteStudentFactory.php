<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NoteStudent;
use Faker\Generator as Faker;

$factory->define(NoteStudent::class, function (Faker $faker) {
    return [
        'value' => $faker->biasedNumberBetween(0, 20, 'sqrt')
    ];
});
