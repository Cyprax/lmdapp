<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function students() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
