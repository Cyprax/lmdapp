<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecue extends Model
{
    public function coursesAttendedBy(User $student, Classe $classe, Semester $semester, Ue $ue) {
        /*return $this->courses()->get()->filter( function(Course $course) use ($student) {
            return $course->hasStudent($student);
        });*/
        $courses = $this->courses()->get()->filter( function (Course $course) use ($student, $classe, $semester, $ue) {
            return (
                $course->isClasseConcerned($classe) &&
                $course->hasStudent($student) &&
                $course->semester()->first()->id == $semester->id &&
                $course->ecue()->first()->ue()->first()->id == $ue->id
            );
        })->values();
        return $courses;
    }

    //Relationships

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function ue() {
        return $this->belongsTo(Ue::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasOne */
    public function syllabus() {
        return $this->hasOne(Syllabus::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function courses() {
        return $this->hasMany(Course::class);
    }
}
