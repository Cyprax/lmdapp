<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'nom' => $faker->lastName,
        'prenoms' => $faker->firstName,
        'matricule' => $faker->unique()->userName, //'INP' . $faker->unique()->numberBetween(1000, 9999) . $faker->randomLetter,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('mdp'),
        'gender' => array(true => 'M', false => 'F')[$faker->boolean(75)],
        'title_id' => 1, //User::all()->random(1)->first()->id,
        'status_id' => 1,//null,
        'remember_token' => Str::random(10),
    ];
});
