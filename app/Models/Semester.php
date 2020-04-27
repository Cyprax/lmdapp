<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function uesAttendedBy(User $student, Classe $classe) {
        $courses = $this->courses()->get()->filter( function (Course $course) use ($student, $classe) {
            return $course->isClasseConcerned($classe) && $course->hasStudent($student);
        });
        $ecues = $courses->map( function(Course $course) {
            return $course->ecue()->first();
        })->unique();
        $ues = $ecues->map( function(Ecue $ecue) {
            return $ecue->ue()->first();
        })->unique();
        return $ues;
    }

}
