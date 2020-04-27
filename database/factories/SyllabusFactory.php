<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Syllabus;
use Faker\Generator as Faker;

$factory->define(Syllabus::class, function (Faker $faker) {
    return [
        'data' => $faker->randomHtml(2,3),
    ];
});
