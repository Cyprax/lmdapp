<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';
    const ROLE_PROFESSOR = 'professor';
    const ROLE_DIRECTOR = 'director';
    const ROLE_INSPECTOR = 'inspector';

    const ROLE_DELEGATE = 'delegate'; /*Special*/
    const ROLE_DEFAULT = 'default'; /*Special*/

    //Relationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function users() {
        return $this->belongsToMany(User::class, 'role_users');
    }

    //Utils
    /** @return integer */
    static public function getRoleId($roleName) {
        return self::where('name', $roleName)->first()->id;
    }
}

