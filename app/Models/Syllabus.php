<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function ecue() {
        return $this->belongsTo(Ecue::class);
    }
}
