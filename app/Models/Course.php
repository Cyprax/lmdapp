<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function isGrouped() {
        return $this->coursable_type === Group::class;
    }
    public function typeCourse() {
        if (!$this->isGrouped()) {
            return 'Cours normal';
        }
        return $this->coursable()->first()->groupState()->first()->label; //or description
    }

    public function hasStudent(User $student) {
        return $this->coursable()->first()->hasStudent($student);
    }
    public function isClasseConcerned(Classe $classe) {
        if (!$this->isGrouped()) { //classe
            return $this->coursable_id == $classe->id;
        } else { //grouped
            return; //A gerer
        }
    }

    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function courseState() {
        return $this->belongsTo(CourseState::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function semester() {
        return $this->belongsTo(Semester::class);
    }

        //Users
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function professor() {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function ecue() {
        return $this->belongsTo(Ecue::class);
    }

    /** @return \Illumiinate\Database\Eloquent\Collection */
    public function ue() {
        return $this->ecue()->first()->ue()->first();
    }

        //Seance
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function seances() {
        return $this->hasMany(Seance::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function notes() {
        return $this->hasMany(Note::class);
    }

    //Polymorphism here now!!!
    /** @return \Illuminate\Database\Eloquent\Relations\MorphTo */
    public function coursable() {
        return $this->morphTo();
    }
}
