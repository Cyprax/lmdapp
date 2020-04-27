<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function classes() {
        return $this->hasMany(Classe::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function director() {
        return $this->belongsTo(User::class, 'director_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function inspector() {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /** @return \Illuminate\Database\Eloquent\Collection */
    public function classesAttendedBy(User $student) {
        return $this->classes()->get()->filter( function (Classe $classe) use ($student) {
            return $classe->hasStudent($student);
        })->values();
    }
}
