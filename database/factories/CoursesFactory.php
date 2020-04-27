<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Classe;
use App\Models\Course;
use App\Models\CourseState;
use App\Models\Ecue;
use App\Models\Group;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Faker\Generator as Faker;


//Course
/*$factory->state(Course::class, 'groupd', [
    'groupd' => true,
    'coursable_type' => Group::class,
    'coursable_id' => Group::all()->random(1)->first()->id,
]);
$factory->state(Course::class, 'nogroupd', [
    'groupd' => false,
    'coursable_type' => Classe::class,
    'coursable_id' => Classe::all()->random(1)->first()->id,
]);*/

$factory->define(Course::class, function (Faker $faker) {
    return [
        'grouped' => false,
        'coursable_type' => Classe::class,
        'coursable_id' => Classe::all()->random(1)->first()->id,
        'coefficient' => $faker->numberBetween(2,6)/2,
        'ecue_id' => Ecue::all()->random(1)->first()->id,
        'semester_id' => Semester::all()->random(1)->first()->id,
        'course_state_id' => CourseState::all()->random(1)->first()->id,
        'professor_id' => Role::where('label', 'professor')->first()->users()->get()->random(1)->first()->id,
    ];
});

