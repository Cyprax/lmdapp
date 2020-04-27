<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    //Relationships

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function seance() {
        return $this->belongsTo(Seance::class);
    }

}
