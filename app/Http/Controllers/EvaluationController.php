<?php

namespace App\Http\Controllers;

use App\Helpers\ParamsHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Ecue;
use App\Models\Group;
use App\Models\Note;
use App\Models\NoteStudent;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Ue;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    //protected $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->user = auth('api')->user();
    }

    //DATA
    public function getBeforeEvaluation(Request $request) {
        $user = auth('api')->user();
        $roleName = $request->roleName;
        return response()->json( $this->beforeEvaluation($user, $roleName) );
    }

    public function getDataEvaluation(Request $request) {
        $user = auth('api')->user();
        $roleName = $request->roleName;
        $params = collect( $request->params );
        return response()->json( $this->dataEvaluation($user, $roleName, $params) );
    }

    //CRUD

    /** @return mixed */
    public function beforeEvaluation(User $user, String $roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->beforeEvaluationAttended($user);
                break;
            case Role::ROLE_PROFESSOR:
                return $this->beforeEvaluationTeached($user);
                break;
            case Role::ROLE_DIRECTOR:
                return $this->beforeEvaluationDirected($user);
                break;
            default:
                return null;
                break;
        }
    }

    public function dataEvaluation(User $user, String $roleName, Collection $params) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->dataEvaluationAttended($user, $params);
                break;
            case Role::ROLE_PROFESSOR:
                return $this->dataEvaluationTeached($user, $params);
                break;
            case Role::ROLE_DIRECTOR:
                return $this->dataEvaluationDirected($user, $params);
                break;
            default:
                return null;
                break;
        }
    }

    //////////////////SUBFUNCTIONS

    /** @return \Illuminate\Support\Collection */
    public function beforeEvaluationAttended(User $student) {
        $classes = $student->classesAttended()->get();;
        $classes->each( function(Classe $classe) use ($student) {
            $classe->meta = [ 'currentOption' => 'classe', 'currentText' => 'Classe', 'nextOption' => 'semesters'];
            $classe->semesters = $classe->semesters();

            $classe->semesters->each( function(Semester $semester) use ($student, $classe) {
                $semester->meta = [ 'currentOption' => 'semester', 'currentText' => 'Semestre', 'nextOption' => 'ues'];
                $semester->ues = $semester->uesAttendedBy($student, $classe);

                $semester->ues->each( function(Ue $ue) use ($student, $classe, $semester) {
                    $ue->meta = [ 'currentOption' => 'ue', 'currentText' => "Unité d'enseignenemt", 'nextOption' => 'courses'];
                    $ecues = $ue->ecuesAttendedBy($student, $classe, $semester);

                    $ecues->each( function(Ecue $ecue) use ($student, $classe, $semester, $ue) {
                        $ecue->courses = $ecue->coursesAttendedBy($student, $classe, $semester, $ue);

                        $ecue->courses->each( function(Course $course) use ($ecue) {
                            $course->label = $ecue->label;
                            $course->meta = [ 'currentOption' => 'course', 'currentText' => 'Cours enseignés', 'nextOption' => null];
                        });
                    });

                    $ue->courses = $ecues->flatMap->courses;

                });
            });

        });

        $params = [
            'params' => [
                ['label' => 'classe', 'descript' => 'Classe', 'next' => 'semesters'],
                ['label' => 'semester', 'descript' => 'Semestre', 'next' => 'ues'],
                ['label' => 'ue', 'descript' => 'UE', 'next' => 'courses'],
                ['label' => 'course', 'descript' => 'Cours', 'next' => null]
            ],
            'first' => $classes
        ];
        return $params;
    }
    /** @return \Illuminate\Support\Collection */
    public function beforeEvaluationTeached(User $professor) {
        $semesters = $professor->coursesTeached()->get()->map( function (Course $course) {
            return $course->semester()->first();
        })->unique();

        $semesters->each( function (Semester $semester) use ($professor) {
            $semester->meta = [ 'currentOption' => 'semester', 'currentText' => 'Semestre', 'nextOption' => 'coursables' ];

            $semester->coursables = $professor->coursesTeached()->where('semester_id', $semester->id)->get()->map(function (Course $course) {
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
            });
        });
        return $semesters;
    }
    /** @return \Illuminate\Support\Collection */
    public function beforeEvaluationDirected(User $director) {
        $filieres = $director->filieresDirected();
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
        return $semesters;

        return;
    }

    /** @return \Illuminate\Support\Collection */
    public function dataEvaluationAttended(User $student, Collection $params) {
        $items = [];
        $fields = [];
        $meta = $this->genericMeta();

        if ($params->last()) {
            switch ($params->last()['label']) {
                case 'classe': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ],
                        [ 'key' => 'description', 'label' => 'Semestre' ], //'key' => 'label'
                        [ 'key' => 'moy', 'label' => 'Moyenne' ],
                        [ 'key' => 'sort', 'label' => 'Rang' ],
                        [ 'key' => 'status', 'label' => 'Statut' ],
                    ];
                    $classe = Classe::find( ParamsHelper::paramFirstIdOf($params, 'classe') );
                    $semesters = $classe->semesters();
                    $semesters->each( function(Semester $semester) {
                        $semester->moy = null; //???
                        $semester->sort = null; //???
                        $semester->status = null; //???
                    });
                    $items = $semesters;
                    break;
                }
                case 'semester': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ], //course_id => ecue_id
                        [ 'key' => 'label', 'label' => "UE" ], //???
                        [ 'key' => 'cect', 'label' => 'CECT' ],
                        [ 'key' => 'moy', 'label' => 'Moyenne' ],
                        [ 'key' => 'sort', 'label' => 'Rang' ]
                    ];

                    $semester = Semester::find( ParamsHelper::paramFirstIdOf($params, 'semester') );
                    $classe = Classe::find( ParamsHelper::paramIdOf($params, 'classe') );
                    $ues = $semester->uesAttendedBy($student, $classe);
                    $ues->each( function(Ue $ue) use ($classe, $semester) {
                        $ue->moy = null; //???
                        $ue->sort = null; //???
                        $ue->cect = null;//$classe->cectOf($semester, $classe) $ue->cectOf($classe, $semester);
                    });
                    $items = $ues;
                    break;
                }
                case 'ue': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ], //course_id => ecue_id
                        [ 'key' => 'ecue', 'label' => "Cours (ECUE)" ], //???
                        [ 'key' => 'coefficient', 'label' => 'Coefficient' ],
                        [ 'key' => 'moy', 'label' => 'Moyenne' ],
                        [ 'key' => 'sort', 'label' => 'Rang' ],
                        [ 'key' => 'type', 'label' => 'Type de cours' ],
                        [ 'key' => 'prof', 'label' => "Professeur" ] //???Incomplete
                    ];

                    $ue = Ue::find( ParamsHelper::paramFirstIdOf($params, 'ue') );
                    $idClasse = ParamsHelper::paramIdOf($params, 'classe');
                    $idSemester = ParamsHelper::paramIdOf($params, 'semester');
                    $courses = $ue->coursesAttendedBy($student, Classe::find($idClasse), Semester::find($idSemester));
                    $courses->each( function(Course $course) {
                        $course->ecue = $course->ecue()->first()->label;
                        $course->moy = null; //???
                        $course->sort = null; //???
                        $course->type = $course->typeCourse();
                        $course->prof = $course->professor()->first()->matricule; //->appelation
                        //$course->value = $note->noteStudent($student)->value;
                    });
                    $items = $courses;
                    break;
                }
                case 'course': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ],
                        [ 'key' => 'type', 'label' => "Type d'évaluation" ], //???
                        [ 'key' => 'value', 'label' => 'Note' ],
                        [ 'key' => 'coefficient', 'label' => 'Coefficient' ],
                        [ 'key' => 'date', 'label' => 'Date' ],
                        [ 'key' => 'description', 'label' => "Description" ]
                    ];
                    $course = Course::find( $params->where('label', 'course')->first()['id'] );
                    $notes = $course->notes()->get();
                    $notes->each( function(Note $note) use ($student) {
                        $note->value = $note->noteStudent($student)->value;
                    });
                    $items = $notes;
                    break;
                }
                default:
                    break;
            }

        }

        return [
            "params" => $params,
            "items" => $items,
            "fields" => $fields,
            "meta" => $meta
        ];
    }

    /** @return \Illuminate\Support\Collection */
    public function dataEvaluationTeached(User $professor, Collection $params) {
        $items = [];
        $fields = [];
        $meta = $this->genericMeta();

        if ($params->last()) {
            $coursable = null;
            switch ($params->last()['label']) {
                /*case 'semester': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ],
                        [ 'key' => 'description', 'label' => 'Semestre' ], //'key' => 'label'
                        [ 'key' => 'moy', 'label' => 'Moyenne' ],
                        [ 'key' => 'sort', 'label' => 'Rang' ],
                        [ 'key' => 'status', 'label' => 'Statut' ],
                    ];
                    $classe = Classe::find( ParamsHelper::paramFirstIdOf($params, 'classe') );
                    $semesters = $classe->semesters();
                    $semesters->each( function(Semester $semester) {
                        $semester->moy = null; //???
                        $semester->sort = null; //???
                        $semester->status = null; //???
                    });
                    $items = $semesters;
                    break;
                }*/
                case 'classe': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ], //course_id
                        [ 'key' => 'label', 'label' => "Cours (ECUE)" ], //???
                        [ 'key' => 'moy', 'label' => 'Moyenne de la classe' ],
                        [ 'key' => 'coefficient', 'label' => 'Coefficient' ],
                        [ 'key' => 'status', 'label' => 'Etat du cours' ],
                    ];
                    $coursable = Classe::find( ParamsHelper::paramFirstIdOf($params, 'classe') );
                    $semester = Semester::find( ParamsHelper::paramIdOf($params, 'semester') );
                    $courses = $professor->coursesTeached()->get();
                    $courses = $courses->filter( function(Course $course) use ($coursable, $semester) {
                        return (
                            $course->coursable()->first() == $coursable &&
                            $course->semester()->first() == $semester
                        );
                    })->values();
                    $courses->each( function (Course $course) {
                        $course->label = $course->ecue()->first()->label;
                        $course->moy = null;//???
                        $course->status = null;//???
                    });

                    $items = $courses;
                    break;
                }
                case 'group': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ], //course_id
                        [ 'key' => 'label', 'label' => "Cours (ECUE)" ], //???
                        [ 'key' => 'moy', 'label' => 'Moyenne de la classe' ],
                        [ 'key' => 'coefficient', 'label' => 'Coefficient' ],
                        [ 'key' => 'status', 'label' => 'Etat du cours' ],
                    ];
                    $coursable = Group::find( ParamsHelper::paramFirstIdOf($params, 'group') );
                    $semester = Semester::find( ParamsHelper::paramIdOf($params, 'semester') );
                    $courses = $professor->coursesTeached()->get();
                    $courses = $courses->filter( function(Course $course) use ($coursable, $semester) {
                        return (
                            $course->coursable()->first() == $coursable &&
                            $course->semester()->first() == Semester::find($semester)
                        );
                    })->values();
                    $courses->each( function (Course $course) {
                        $course->label = $course->ecue()->first()->label;
                        $course->moy = null;//???
                        $course->status = null;//???
                    });

                    $items = $courses;
                    break;
                }
                case 'course': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ],
                        [ 'key' => 'type', 'label' => "Type d'évaluation" ], //???
                        [ 'key' => 'coefficient', 'label' => 'Coefficient' ],
                        [ 'key' => 'date', 'label' => 'Date' ],
                        [ 'key' => 'description', 'label' => "Description" ]
                    ];
                    $course = Course::find( $params->where('label', 'course')->first()['id'] );
                    $notes = $course->notes()->get();
                    $items = $notes;
                    break;
                }
                case 'note': {
                    $fields = [
                        [ 'key' => 'id', 'visible' => false ],
                        [ 'key' => 'matricule', 'label' => "matricule" ],
                        [ 'key' => 'nom', 'label' => 'Nom' ],
                        [ 'key' => 'prenoms', 'label' => 'Prénoms' ],
                        [ 'key' => 'value', 'label' => 'Note' ],
                        [ 'key' => 'liking', 'label' => "Appréciation" ] //???
                    ];
                    $notes = Note::find( $params->where('label', 'note')->first()['id'] );
                    $noteStudents = $notes->noteStudentsFirstOrCreate();
                    $noteStudents->each( function(NoteStudent $noteStudent) use ($professor) {
                        $student = $noteStudent->student();
                        $noteStudent->matricule = $student->matricule;
                        $noteStudent->nom = $student->nom;
                        $noteStudent->prenoms = $student->prenoms;
                        $noteStudent->liking = null; //????
                    });
                    $items = $noteStudents;
                    break;
                }
                default:
                    break;
            }

        }

        return [
            "params" => $params,
            "items" => $items,
            "fields" => $fields,
            "meta" => $meta
        ];
    }

    /** @return \Illuminate\Support\Collection */
    public function dataEvaluationDirected(User $director, Collection $params) {
        return [
            "data" => null,
            "fields" => null,
        ];
    }

    public function genericMeta() {
        $meta = [
            "numerotable" => false,
            "header" => [
                "filterable" => false, //searchable
                "creatable" => false, //newable | addable
                "export" => [
                    "xlsx" => false,
                    "pdf" => false,
                    "docx" => false,
                    "png" => false
                ]
            ],
            "actions" => [
                "detaillable" => false, //readable
                "updatable" => false, //modifyable
                "deletable" => false
            ]
        ];
        return $meta;
    }

}
