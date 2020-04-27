<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupState extends Model
{
    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function groups() {
        return $this->hasMany(Group::class);
    }
}
