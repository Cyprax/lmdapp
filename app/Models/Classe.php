<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    /** @return bool */
    public function hasStudent(User $student) {
        return !is_null(
            ClasseStudent::where('classe_id', $this->id)
            ->where('student_id', $student->id)
            ->first()
        );
        /*return boolval (
            ClasseStudent::where('classe_id', $this->id)
            ->where('student_id', $student->id)
            ->first()
        );*/
    }

    public function effectif() {
        return $this->students()->count();
    }

    /** @return \Illuminate\Support\Collection */
    public function delegates() {
        return $this->students()->get()->filter(
            function(User $student) {
                return $student->pivot->delegated == true;
            }
        );
    }

    //Relationships

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function filiere() {
        return $this->belongsTo(Filiere::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function students() {
        return $this->belongsToMany(User::class, 'classe_students', 'classe_id', 'student_id')->withPivot('delegated');
    }

    //Morph

    /** @return \Illuminate\Database\Eloquent\Relations\MorphMany */
    public function courses() {
        return $this->morphMany(Course::class, 'coursable');
        //return $this->morphToMany(Course::class, 'coursable');
    }

    /** @return \Illuminate\Support\Collection */
    public function semesters() {
        return $this->courses()->get()->map( function(Course $course) {
            return $course->semester()->first();
        } )->unique();
    }
}
