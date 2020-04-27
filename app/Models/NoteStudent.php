<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteStudent extends Model
{
    //
    protected $fillable = ['value'];


    public function student() {
        return User::find($this->student_id);
    }
    public function note() {
        return Note::find($this->note_id);
    }
}
