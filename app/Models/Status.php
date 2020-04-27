<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //

    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function users() {
        return $this->hasMany(User::class);
    }
}
