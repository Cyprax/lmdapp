<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function ecues() {
        return $this->hasMany(Ecue::class);
    }

    public function ecuesAttendedBy(User $student, Classe $classe, Semester $semester) {
        $courses = $student->coursesAttended()->filter( function (Course $course) use ($classe, $semester) {
            return (
                $course->ecue()->first()->ue_id == $this->id &&
                $course->isClasseConcerned($classe) &&
                $course->semester()->first()->id == $semester->id
            );
        });
        //$courses = $this->coursesAttendedBy($student, $classe, $semester);
        $ecues = $courses->map( function (Course $course) {
            return $course->ecue()->first();
        })->unique()->values();
        return $ecues;
    }

    public function coursesAttendedBy(User $student, Classe $classe, Semester $semester) {
        $courses = $student->coursesAttended()->filter( function (Course $course) use ($classe, $semester) {
            return (
                $course->ecue()->first()->ue_id == $this->id &&
                $course->isClasseConcerned($classe) &&
                $course->semester()->first()->id == $semester->id
            );
        })->values();
        return $courses;
    }

    ////////////////////////

    /*public function getEcuesAttended(User $student) {
        $this->ecues = $this->ecues()->get();

        $this->ecues->each( function(Ecue $ecue) use ($student) {
            $ecue->courses = $ecue->courses()->get()->filter( function(Course $course) use ($student) {
                return $course->coursable()->first()->hasStudent($student);
                //Is it enough: explore case with student in other groups
            });
        });
        $this->ecues = $this->ecues->filter( function(Ecue $ecue, $key) {
            return $ecue->courses->count();
        });
        $this->ecues->each( function(Ecue $ecue) {
            $ecue->courses->each( function(Course $course) {
                $course->professor = $course->professor()->first();
                $course->semester = $course->semester()->first();
                $course->courseState = $course->courseState()->first();
                $course->isGrouped = $course->isGrouped();
                $course->countEvals = $course->notes()->count();
            });
        });
    }*/
}
