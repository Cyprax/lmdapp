<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['coefficient', 'date', 'descripition', 'course_id'];

    //Reationships
    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function students() {
        return $this->belongsToMany(User::class, 'note_students', 'note_id', 'student_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function course() {
        return $this->belongsTo(Course::class);
    }

    /** @return \Illuminate\Support\Collection */
    public function noteStudent(User $student) {
        return NoteStudent::firstOrCreate(
            ['note_id' => $this->id, 'student_id' => $student->id],
            ['value' => null]
        );
    }

    //?????????????
    public function noteStudentsFirstOrCreate() {
        $coursable = $this->course()->first()->coursable()->first();
        $currentNote = $this;
        if ($coursable instanceof Classe) {
            $notesStudent = $coursable->students()->get()->map( function (User $student) use ($currentNote) {
                return $currentNote->noteStudent($student);
            });
            return $notesStudent;
        }
        else {  //instanceof Group

        }
    }
}
