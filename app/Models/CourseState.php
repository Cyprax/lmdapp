<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseState extends Model
{
    //Relationships

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function courses() {
        return $this->hasMany(Course::class);
    }
}
