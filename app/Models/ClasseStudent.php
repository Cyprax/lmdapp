<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasseStudent extends Model
{
    //
    /** @return bool */
    public function isDelegated() {
        return boolval ( $this->isDelegated() );
    }
}
