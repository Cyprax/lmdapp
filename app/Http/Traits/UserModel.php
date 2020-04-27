<?php

namespace App\Http\Traits;

use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Ecue;
use App\Models\Filiere;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Seance;
use App\Models\Semester;
use App\Models\Status;
use App\Models\Title;
use App\Models\Ue;

trait UserModel
{

    /** @return bool */
    public function isDelegated() { //Verifier si l'utilisateur est un chef de classe/group
        return boolval ( $this->coursablesDelegated() );
        /*coursables = $this->coursablesDelegated();
        return boolval ( $coursables[Classe::class] ) || boolval ( $coursables[Group::class] );
        */
    }

    /** @return bool */
    public function isClasseDelegated() {
        return boolval( $this->classesDelegated() );
    }

    /** @return bool */
    public function isgroupDelegated() {
        return boolval( $this->groupsDelegated() );
    }

    //Relationships -- in UserRelationshipsTrait

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function roles() {
        return $this->belongsToMany(Role::class, 'role_users')->withTimestamps();
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function title() {
        return $this->belongsTo(Title::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function status() {
        return $this->belongsTo(Status::class);
    }

    /** @return mixed */
    public function notif($notifParam = Notification::NOTIF_ALL) {
        switch ($notifParam) {
            case Notification::NOTIF_FROM:
                return $this->notificationsFrom();
            case Notification::NOTIF_TO:
                return $this->notificationsTo();
            case Notification::NOTIF_ALL:
                return array(
                    'from' => $this->notificationsFrom(),
                    'to' => $this->notificationsTo()
                );
            default:
                return null;
        }
    }

    /** @return mixed */
    public function filieres($roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->filieresAttended();
            case Role::ROLE_PROFESSOR:
                return $this->filieresTeached();
            case Role::ROLE_DIRECTOR:
                return $this->filieresDirected();
            case Role::ROLE_INSPECTOR:
                return $this->filieresInspected();
            default:
                return null;
        }
    }

    /** @return mixed */
    public function classes($roleName) {
        //director - inspector - student
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->classesAttended();
                break;
            case Role::ROLE_PROFESSOR:
                return $this->classesTeached();
                break;
            case Role::ROLE_DIRECTOR:
                return $this->classesDirected();
                break;
            case Role::ROLE_INSPECTOR:
                return $this->classesInspected();
                break;
            default:
                return null;
                break;
        }
    }

    /** @return mixed */
    public function groups($roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->groupsAttended();
            case Role::ROLE_PROFESSOR:
                return $this->groupsTeached();
            case Role::ROLE_DIRECTOR:
                return $this->groupsAttended();
            case Role::ROLE_INSPECTOR:
                return $this->groupsTeached();
            default:
                return null;
        }
    }

    /** @return mixed */
    public function courses($roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->coursesAttended();
                break;
            case Role::ROLE_PROFESSOR:
                return $this->coursesTeached();
                break;
            case Role::ROLE_DIRECTOR:
                return $this->coursesDirected();
                break;
            case Role::ROLE_INSPECTOR:
                return $this->coursesInspected();
                break;
            default:
                return null;
                break;
        }
    }

    /** @return mixed */
    public function coursables($roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->coursablesAttended();
                break;
            case Role::ROLE_PROFESSOR:
                return $this->coursablesTeached();
                break;
            case Role::ROLE_DIRECTOR:
                return $this->coursablesDirected();
                break;
            case Role::ROLE_INSPECTOR:
                return $this->coursablesInspected();
                break;
            default:
                return null;
                break;
        }
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function notes() {
        return $this->belongsToMany(Note::class, 'note_students', 'student_id', 'note_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function bulletins() { //Students
        return $this->hasMany(Bulletin::class, 'student_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function seances() {
        return $this->belongsToMany(Seance::class, 'absences', 'student_id', 'seance_id');
    }



    ///////////////////////////////////////////////////////////

    //Notifications

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function notificationsFrom() {
        return $this->hasMany(Notification::class, Notification::NOTIF_FROM);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function notificationsTo() {
        return $this->hasMany(Notification::class, Notification::NOTIF_TO);
    }

    //Filieres

    /** @return \Illuminate\Support\Collection */
    public function filieresAttended() {
        return $this->classesAttended()->get()->map->filiere()->flatMap->get()->unique();
    }

    /** @return \Illuminate\Support\Collection */
    public function filieresTeached() {
        return $this->classesTeached()->map->filiere()->flatMap->get()->unique();;
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function filieresDirected() {
        return $this->hasMany(Filiere::class, 'director_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function filieresInspected() {
        return $this->hasMany(Filiere::class, 'director_id');
    }

    //Classes

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function classesAttended() {
        return $this->belongsToMany(Classe::class, 'classe_students', 'student_id', 'classe_id');
    }

    /** @return \Illuminate\Support\Collection */
    public function classesTeached() {
        $classes = $this->coursesTeached()->where('coursable_type', Classe::class)->get()->map->coursable();
        //return $classes;
        return $classes->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function classesDirected() {
        $listClasses = $this->filieresDirected()->get()->map->classes();
        //return $listClasses;
        return $listClasses->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function classesInspected() {
        $listClasses = $this->filieresDirected()->get()->map->classes();
        //return $listClasses;
        return $listClasses->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function classesDelegated() {
        return $this->classesAttended()->get()->filter(
            function(Classe $classe, $key) {
                return $classe->delegates()->keyBy('id')->has($this->id);
            }
        );
    }

    //Groups

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany */
    public function groupsAttended() {
        return $this->belongsToMany(Group::class, 'group_students', 'student_id');
    }

    /** @return \Illuminate\Support\Collection */
    public function groupsTeached() {
        $groups = $this->coursesTeached()->where('coursable_type', Group::class)->get()->map->coursable();
        //return $groups;
        return $groups->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function groupsDirected() {
        $groups = $this->coursesDirected()->where('coursable_type', Group::class)->map->coursable();
        //return $groups;
        return $groups->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function groupsInspected() {
        $groups = $this->coursesInspected()->where('coursable_type', Group::class)->map->coursable();
        //return $groups;
        return $groups->flatMap->get();
    }

    /** @return \Illuminate\Support\Collection */
    public function groupsDelegated() {
        return $this->groupsAttended()->get()->filter(
            function(Classe $group, $key) {
                return $group->delegates()->keyBy('id')->has($this->id);
            }
        );
    }

    /** @return \Illuminate\Support\Collection */
    public function coursablesAttended() {
        return $this->classesAttended()->get()->union(
            $this->groupsAttended()->get()
        );
        /*return collect([
            Classe::class => $this->classesAttended(),
            Group::class => $this->groupsAttended(),
        ])*/
    }

    /** @return \Illuminate\Support\Collection */
    public function coursablesTeached() {
        return Course::where('professor_id', $this->id)->get()->map->coursable()->flatMap->get();
        /*return collect([
            Classe::class => $this->classesTeached(),
            Group::class => $this->groupsTeached()
        ]);*/
    }

    /** @return \Illuminate\Support\Collection */
    public function coursablesInspected() {
        return $this->classesInspected()->union(
            $this->groupsInspected()
        );
        /*return collect([
            Classe::class => $this->classesInspected(),
            Group::class => $this->groupsInspected()
        ]);*/
    }

    /** @return \Illuminate\Support\Collection */
    public function coursablesDirected() {
        return $this->classesDirected()->union(
            $this->groupsDirected()
        );
        /*return collect([
            Classe::class => $this->classesDirected(),
            Group::class => $this->groupsDirected()
        ]);*/
    }

    /** @return \Illuminate\Support\Collection */
    public function coursablesDelegated() {
        return $this->classesDelegated()->union(
            $this->groupsDelegated()
        );
        /*return collect([
            Classe::class => $this->classesDelegated(),
            Group::class => $this->groupsDelegated()
        ]);*/
    }

    //Courses

    /** @return \Illuminate\Support\Collection */
    public function coursesAttended() {
        $coursesClasses = $this->classesAttended()->get()->map->courses();
        $coursesGroups = $this->groupsAttended()->get()->map->courses();
        $courses = $coursesClasses->union($coursesGroups);
        //return $courses;
        return $courses->flatMap->get();//->unique();
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function coursesTeached() {
        return $this->hasMany(Course::class, 'professor_id');
    }

    /** @return \Illuminate\Support\Collection */
    public function coursesDirected() {
        //Compréhension complexe: Cours concernant des étudiants fréquentant des classes dirigées par $this
        $classes = $this->classesDirected();
        return $classes->map->courses()->flatMap->get()->unique();
    }

    /** @return \Illuminate\Support\Collection */
    public function coursesInspected() {
        $classes = $this->classesInspected();
        return $classes->map->courses()->flatMap->get()->unique();
    }


    ////////////////////////////////////////
    /** @return mixed */
    public function beforeEvaluation($roleName) {
        switch ($roleName) {
            case Role::ROLE_STUDENT:
                return $this->beforeEvaluationAttended();
                break;
            case Role::ROLE_PROFESSOR:
                return $this->beforeEvaluationTeached();
                break;
            case Role::ROLE_DIRECTOR:
                return $this->beforeEvaluationDirected();
                break;
            default:
                return null;
                break;
        }
    }

    public function beforeEvaluationAttended() {
        $classes = $this->classesAttended()->get();

        $classes->each( function(Classe $classe) {
            $classe->effectif = $classe->effectif();
            $classe->delegates = $classe->delegates();
            $classe->filiere = $classe->filiere()->first();

            $classe->semesters = $classe->semesters();
            $classe->semesters->each( function(Semester $semester) use ($classe) {
                $semester->ues = $semester->uesByClasse( Classe::find($classe->id) );

                $semester->ues->each( function(Ue $ue) {
                    $ue->ecues = $ue->ecues()->get();

                    $ue->ecues->each( function(Ecue $ecue) {
                        $ecue->courses = $ecue->courses()->get();

                        $ecue->courses->each( function(Course $course) {
                            $course->professor = $course->professor()->first();
                            $course->courseState = $course->courseState()->first();
                            $course->isGrouped = $course->isGrouped();
                            $course->evals = $course->notes()->get();
                        });

                        /*$ecue->courses = $ecue->courses->filter( function (Course $course) {
                            return $course->count();
                        });*/
                    });

                    /*$ue->ecues = $ue->ecues->filter( function (Ecue $ecue) {
                        return $ecue->count();
                    });*/
                });

                /*$semester->ues = $semester->ues->filter( function (Ue $ue) {
                    return $ue->count();
                });*/
            });
        });

        return $classes;
    }

    public function dataEvaluation($roleName, $evalId) {
        return;
    }

}
