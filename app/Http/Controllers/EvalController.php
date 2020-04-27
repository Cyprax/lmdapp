<?php

namespace App\Http\Controllers;

use App\Helpers\ParamsHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Ecue;
use App\Models\Filiere;
use App\Models\Group;
use App\Models\Note;
use App\Models\NoteStudent;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Ue;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class EvalController extends Controller
{
    protected $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth('api')->user();
        $this->meta = (object) ([
            "numerotable" => true,
            "groupable" => false,
            "header" => (object) [
                "filterable" => true, "creatable" => false,
                "export" => (object) [ "xlsx" => false, "pdf" => false, "docx" => false, "png" => false ]
            ],
            "actions" => (object) [ "readable" => true, "updatable" => false, "deletable" => false ]
        ]);
    }

    //--------------------------------------------------------------------------------
    //PARAMS && CRUD FUNCTIONS--------------------------------------------------------

    /** @return \Illuminate\Http\JsonResponse */
    public function params(Request $request) {
        $role = $request->role;

        $params = null; //Collection
        switch ($role) {
            case Role::ROLE_STUDENT:
                $params = $this->paramsAttended($this->user);
                break;
            case Role::ROLE_PROFESSOR:
                $params = $this->paramsTeached($this->user);
                break;
            case Role::ROLE_DIRECTOR:
                $params = $this->paramsDirected($this->user);
                break;
        }
        return response()->json( $params );
    }

    /** @return \Illuminate\Http\JsonResponse */
    public function index(Request $request) {
        $role = $request->role;
        $paramMode = $request->paramMode;
        $params = collect( $request->params );

        $datas = null;
        switch ($role) {
            case Role::ROLE_STUDENT:
                $datas = $this->datasAttended($this->user, $params, $paramMode);
                break;
            case Role::ROLE_PROFESSOR:
                $datas = $this->datasTeached($this->user, $params, $paramMode);
                break;
            case Role::ROLE_DIRECTOR:
                $datas = $this->datasDirected($this->user, $params, $paramMode);
                break;
        }
        return response()->json( $datas );
    }

    /** @return \Illuminate\Http\JsonResponse */
    public function store(Request $request) {
        $role = $request->role;
        $params = (object)($request->params);

        $result = false;
        switch ($role) {
            case Role::ROLE_PROFESSOR: //Créer/Enregistrer une nouvelle évaluation
                $result = $this->storeNote($params);
                break;
        }
        return response()->json($result);
    }

    /** @return \Illuminate\Http\JsonResponse */
    public function show(Request $request, $id) {
        $role = $request->role;
        $label = $request->label;

        $item = null;
        switch ($role) {
            case Role::ROLE_STUDENT:
                $item = $this->showAttended($this->user, $label, $id);
                break;
            case Role::ROLE_PROFESSOR:
                $item = $this->showTeached($this->user, $label, $id);
                break;
            case Role::ROLE_DIRECTOR:
                $item = $this->showDirected($this->user, $label, $id);
                break;
        }
        return response()->json( $item );
    }

    /** @return \Illuminate\Http\JsonResponse */
    public function update(Request $request, $id) {
        $role = $request->role;
        $mode = $request->mode;
        $params = (object)($request->params);

        $result = false;
        switch ($role) {
            case Role::ROLE_PROFESSOR: {
                if ($mode == 'note') $result = $this->updateNote($id, $params);
                else if ($mode == 'note-student') $result = $this->updateNoteStudent($id, $params);
            }
        }
        return response()->json($result);
    }

    /** @return \Illuminate\Http\JsonResponse */
    public function destroy(Request $request, $id) {
        $role = $request->role;

        $result = false;
        switch ($role) {
            case Role::ROLE_PROFESSOR:
                $result = $this->destroyNote($id);
                break;
        }
        return response()->json($result);
    }


    //--------------------------------------------------------------------------
    //PARAMS SUBFUNCTION--------------------------------------------------------

    /** @return \Illuminate\Support\Collection */
    public function paramsAttended(User $student) {
        $classes = $student->classesAttended()->get();

        $classes->each( function(Classe $classe) use ($student) {
            $classe->semesters = $classe->semesters();

            $classe->semesters->each( function(Semester $semester) use ($student, $classe) {
                $semester->ues = $semester->uesAttendedBy($student, $classe);

                $semester->ues->each( function(Ue $ue) use ($student, $classe, $semester) {
                    $ecues = $ue->ecuesAttendedBy($student, $classe, $semester);

                    $ecues->each( function(Ecue $ecue) use ($student, $classe, $semester, $ue) {
                        $ecue->courses = $ecue->coursesAttendedBy($student, $classe, $semester, $ue);

                        $ecue->courses->each( function(Course $course) use ($ecue) {
                            $course->label = $ecue->label;
                        });
                    });

                    $ue->courses = $ecues->flatMap->courses;
                });
            });
        });

        $params = [
            'display' => [
                ['id' => 'BY_SEM', 'value' => 'Planche semestrielle'],
                ['id' => 'BY_UE', 'value' => 'Afficher par UE'],
                ['id' => 'BY_ECUE', 'value' => 'Afficher par ECUE'],
            ],
            'meta' => [
                ['label' => 'classe', 'descript' => 'Classe', 'next' => 'semesters', 'if' => '*'],
                ['label' => 'semester', 'descript' => 'Semestre', 'next' => 'ues', 'if' => '*'],
                ['label' => 'ue', 'descript' => 'UE', 'next' => 'courses', 'if' => ['BY_UE', 'BY_ECUE']],
                ['label' => 'course', 'descript' => 'Cours', 'next' => null, 'if' => ['BY_ECUE']]
            ],
            'data' => $classes
        ];
        return $params;
    }
    /** @return \Illuminate\Support\Collection */
    public function paramsTeached(User $professor) {
        $semesters = $professor->coursesTeached()->get()->map( function (Course $course) {
            return $course->semester()->first();
        })->unique();

        $semesters->each( function (Semester $semester) use ($professor) {
            $semester->coursables = $professor->coursesTeached()->where('semester_id', $semester->id)->get()->map(function (Course $course) {
                return $course->coursable()->first();
            })->unique();

            //Classes & groups
            $semester->coursables->each( function ($coursable) use ($professor) {
                $coursable->courses = $coursable->courses()->where('professor_id', $professor->id)->get();

                $coursable->courses->each( function(Course $course) {
                    $course->label = $course->ecue()->first()->label;
                    $course->notes = $course->notes()->get();

                    $course->notes->each( function(Note $note, $key) {
                        $note->label = 'Evaluation N°' . ++$key;
                    });
                });
            });
        });

        $params = [
            'meta' => [
                ['label' => 'semester', 'descript' => 'Semestre', 'next' => 'coursables'],
                ['label' => 'coursable', 'descript' => 'Classe / Groupe', 'next' => 'courses'],
                ['label' => 'course', 'descript' => 'Cours', 'next' => 'notes'],
                ['label' => 'note', 'descript' => 'Evaluation', 'next' => null]
            ],
            'data' => $semesters
        ];
        return $params;
    }
    /** @return \Illuminate\Support\Collection */
    public function paramsDirected( User $director) {
        $filieres = $director->filieresDirected();
        return $filieres;
        $filieres->each( function (Filiere $filiere) use ($director) {
            $filiere->classes = $filiere->classes()->get();

            $filiere->classes->each( function(Classe $classe) use ($director) {
                $classe->semesters = $classe->semesters();

                $classe->semesters->each( function(Semester $semester) use ($director) {
                    $semester->ues = $director->coursesDirected()->where('semester_id', $semester->id)->map(function (Course $course) {
                        return $course->ecue()->first()->ue()->first();
                    })->unique();

                    /*$semester->ue->each( function(Ue $ue) {
                        $ue->ecues = $ue->ecues();
                    });*/
                });
            });

        });
        $semesters = $director->coursesDirected()->map( function (Course $course) {
            return $course->semester()->first();
        })->unique();

        $semesters->each( function (Semester $semester) use ($director) {
            $semester->meta = [ 'currentOption' => 'semester', 'currentText' => 'Semestre', 'nextOption' => 'coursables' ];

            /*$semester->coursables = $professor->coursesTeached()->where('semester_id', $semester->id)->get()->map(function (Course $course) {
                return $course->coursable()->first();
            })->unique();

            //Classes & groups
            $semester->coursables->each( function ($coursable) use ($professor) {
                $coursable->meta = [
                    'currentOption' => ($coursable instanceof Classe) ? 'classe' : 'group',
                    'currentText' => 'Classes et/ou groupes enseignés',
                    'nextOption' => 'courses'
                ];

                $coursable->courses = $coursable->courses()->where('professor_id', $professor->id)->get();
                $coursable->courses->each( function(Course $course) {
                    $course->meta = [ 'currentOption' => 'course', 'currentText' => 'Cours enseigné', 'nextOption' => 'notes'];
                    $course->label = $course->ecue()->first()->label;

                    $course->notes = $course->notes()->get();
                    $course->notes->each( function(Note $note, $key) {
                        $note->meta = [ 'currentOption' => 'note', 'currentText' => 'Evaluations', 'nextOption' => null];
                        $note->label = 'Evaluation N°' . ++$key;
                    });
                });
            });*/
        });

        $params = [
            'meta' => [
                ['label' => 'filiere', 'descript' => 'Filière', 'next' => 'semestres'],
                ['label' => 'classe', 'descript' => 'Classe', 'next' => 'ues'],
                ['label' => 'semester', 'descript' => 'Semestre', 'next' => 'classes'],
                ['label' => 'ue', 'descript' => 'UE', 'next' => 'ecues'],
                ['label' => 'ecue', 'descript' => 'ECUE', 'next' => null],
            ],
            'data' => $filieres
        ];
        return $params;
    }


    //-------------------------------------------------------------------------
    //INDEX SUBFUNCTION--------------------------------------------------------

    /** @return \Illuminate\Support\Collection */
    public function datasAttended(User $student, Collection $params, String $paramMode) {
        //On suppose qu'aucune donnée fournie n'est nulle

        $columns = [ //{'field', 'hidden', 'label', 'type', 'sortable', 'editable', 'editMode'}
            [ 'field' => 'id', 'hidden' => true ],
            [ 'field' => 'date', 'label' => 'Date', 'type' => 'text'],
            [ 'field' => 'type', 'label' => 'Type' ],
            [ 'field' => 'value', 'label' => 'Note' ],
            [ 'field' => 'maxNote', 'hidden' => true ],
            [ 'field' => 'coefficient', 'label' => 'Coefficient' ],
            [ 'field' => 'description', 'label' => 'Description' ]
        ];

        //$params = [{id_classe, classe}, {id_semester, semester}, {id_ue, ue}, {id_course, course}]
        $course = Course::find( $params->where('label', 'course')->first()['id'] );
        $notes = null;
        if ($course) {
            $notes = $course->notes()->get();
            $notes->each( function(Note $note) use ($student) {
                $note->value = $note->noteStudent($student)->value;
                $note->type = null;
                $note->maxNote = null;
                //$note = $note->only(['id', 'date', 'type', 'value', 'maxNote', 'coefficient', 'description'])
            });
        }
        $rows = $notes;

        $this->meta->numerotable = false;
        return [
            'columns' => $columns ?? [],
            'rows' => $rows ?? [],
            'meta' => $this->meta
        ];
    }

    /** @return \Illuminate\Support\Collection */
    public function datasGroupedAttended(User $student, Collection $params) {
        //On suppose qu'aucune donnée fournie n'est nulle

        $columns = [ //{'field', 'hidden', 'label', 'type', 'sortable', 'editable', 'editMode'}
            [ 'field' => 'id', 'hidden' => true ],
            //[ 'field' => 'date', 'label' => 'Date', 'type' => 'text'],
            [ 'field' => 'type', 'label' => 'Type' ],
            [ 'field' => 'value', 'label' => 'Note' ],
            [ 'field' => 'maxNote', 'hidden' => true ],
            [ 'field' => 'coefficient', 'label' => 'Coefficient' ],
            [ 'field' => 'description', 'label' => 'Description' ]
        ];
        $rows = collect([]);

        //$params = [{id_classe, classe}, {id_semester, semester}, {id_ue, ue}, {id_course, course}]
        $course = Course::find( $params->where('label', 'course')->first()['id'] );
        $notes = null;
        if ($course) {
            $notes = $course->notes()->get();
            $rows = new Collection();
            $notes->groupBy('date')->each(function(Collection $groupNote, $date) use ($rows, $student) {
                $row = collect();
                $row->put('mode', 'span');
                $row->put('label', $date);
                $row->put('html', false);
                $row->put('children',
                    $groupNote->map( function(Note $note) use ($student) {
                        $note->value = $note->noteStudent($student)->value;
                        $note->type = null;
                        $note->maxNote = null;

                        return $note->only(['id', 'type', 'value', 'maxNote', 'coefficient', 'description']);
                    })
                );
                $rows->push($row);
            });
        }

        $this->meta->numerotable = false;
        $this->meta->groupable = true;
        return [
            'columns' => $columns ?? [],
            'rows' => $rows ?? [],
            'meta' => $this->meta
        ];
    }

    /** @return \Illuminate\Support\Collection */
    public function datasTeached(User $professor, Collection $params, String $paramMode) {
        //On suppose qu'aucune donnée fournie n'est nulle
        $rows = [];
        $columns = [ //{'key', 'hidden', 'text', 'editable', 'editMode'}
            [ 'field' => 'id', 'hidden' => true ],
            [ 'field' => 'matricule', 'label' => 'Matricule' ],
            [ 'field' => 'name', 'label' => 'Nom & Prenoms', 'fusion' => true ],
            [ 'field' => 'value', 'label' => 'Note', 'editable' => true, 'editMode' => 'double-spinbox' ],
            [ 'field' => 'liking', 'label' => 'Appréciation', 'editable' => true, 'editMode' => 'select' ],
        ];

        $notes = Note::find( $params->where('label', 'note')->first()['id'] );
        if ($notes) {
            $noteStudents = $notes->noteStudentsFirstOrCreate();
            $noteStudents->each( function(NoteStudent $noteStudent) use ($professor) {
                $student = $noteStudent->student();
                $noteStudent->matricule = $student->matricule;
                $noteStudent->name = "$student->nom $student->prenoms";
                $noteStudent->liking = null; //????
                //$noteStudent = $noteStudent->only(['id', 'matricule', 'nom', 'prenoms', 'value', 'liking'])
            });
            $rows = $noteStudents;
        }

        $this->meta->actions->updatable = true;
        return [
            'columns' => $columns ?? [],
            'rows' => $rows ?? [],
            'meta' => $this->meta
        ];
    }

    /** @return \Illuminate\Support\Collection */
    public function datasDirected(User $director, Collection $params, String $paramMode) {
        return [
            "data" => null,
            "fields" => null,
        ];
    }

    //------------------------------------------------------------------------
    //STORE SUBFUNCTION-------------------------------------------------------
    /** @return \Illuminate\Support\Collection */
    public function storeNote(Object $params) {
        $note = Note::create( [
            'coefficient' => $params->coefficient,
            'date' => $params->date,
            //'type_id' => $params->type_id,
            'description' => $params->description,
            'course_id' => $params->course_id
        ] );
        return boolval ( $note );

    }


    //------------------------------------------------------------------------
    //SHOW SUBFUNCTION--------------------------------------------------------

    /** @return \Illuminate\Support\Collection */
    public function showAttended(User $student, String $label, $id) {
        $item = collect(null);
        switch($label) {
            case 'eval': {
                break;
            }
        }
        return $item;
    }
    /** @return \Illuminate\Support\Collection */
    public function showTeached(User $professor, String $label, $id) {
        $item = collect(null);
        switch($label) {
            case 'eval': {
                break;
            }
        }
        return $item;
    }
    /** @return \Illuminate\Support\Collection */
    public function showDirected(User $director, String $label, $id) {
        $item = collect(null);
        switch($label) {
            case 'eval': {
                break;
            }
        }
        return $item;
    }


    //------------------------------------------------------------------------
    //UPDATE SUBFUNCTION--------------------------------------------------------

    /** @return \Illuminate\Support\Collection */
    public function updateNote($id, Object $params) { //Mettre à jour une évaluation
        $hasBeenUpdated = false;
        $note = Note::find($id);
        if ($note) {
            $hasBeenUpdated = $note->update(
                [
                    'coefficient' => $params->coefficient,
                    'date' => $params->date,
                    //'type_id' => $params->type_id,
                    'description' => $params->description
                ]
            );
        }
        return $hasBeenUpdated;
    }

    public function updateNoteStudent($id, $params) { //Mettre à jour la note d'un étudiant
        $noteStudent = NoteStudent::updateOrCreate(
            ['id' => $id],
            ['value' => $params->value]
        );
        return boolval( $noteStudent );
    }


    //------------------------------------------------------------------------
    //DESTROY SUBFUNCTION--------------------------------------------------------

    /** @return \Illuminate\Support\Collection */
    public function destroyNote($id) { //Supprimer une note => supprimer les notes des étudiants
        NoteStudent::where('note_id', $id)->delete();
        $hasBeenDestroyed = Note::destroy($id);
        return boolval($hasBeenDestroyed);
    }

}
