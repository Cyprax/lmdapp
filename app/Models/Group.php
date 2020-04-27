<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @return bool */
    public function hasStudent(User $student) {
        return !is_null(
            GroupStudent::where('group_id', $this->id)
            ->where('student_id', $student->id)
            ->first()
        );
    }

    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function groupState() {
        return $this->belongsTo(GroupState::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function students() {
        return $this->belongsToMany(User::class, 'group_students', 'group_id', 'student_id');
    }

    //Morph
    /** @return \Illuminate\Database\Eloquent\Relations\MorphMany */
    public function courses() {
        return $this->morphMany(Course::class, 'coursable');
        //return $this->morphToMany(Course::class, 'coursable');
    }

}
