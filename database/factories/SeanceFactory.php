<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Seance;
use Faker\Generator as Faker;

$factory->define(Seance::class, function (Faker $faker) {
    $isMorning = $faker->boolean();
    return [ //??? A vérifier
        'date' => $faker->dateTimeBetween('now', '6 months')->format('Y-m-d'),
        'period' => collect([
            'h1' => $isMorning,
            'h2' => $isMorning,
            'h3' => $isMorning,
            'h4' => false,
            'h5' => !$isMorning,
            'h6' => !$isMorning,
            'h7' => !$isMorning,
            'h8' => false,
        ])->toJson(),
    ];
});
