<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasOne */
    public function progression() {
        return $this->hasOne(Progression::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function course() {
        return $this->belongsTo(Course::class);
    }

    //Absences _ Etudiants
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function students() {
        return $this->belongsToMany(User::class, 'absences', 'seance_id', 'student_id');
    }
}
