<?php

namespace App\Http\Traits;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait AppTreatment
{
    //Parameters
    private $specialIds = array(
        'default' => 'home',
        'error' => 'error', //'error' => 'home'
        'profile' => 'profile',
        'notif' => 'notif',
        'about' => 'about',
    );

    private $accessiblesAllways = array(
        'error' => array(
            'index' => '', 'id' => 'error', 'view' => '_home', //'view' => '_error'
            'icon' => null, 'title' => 'Erreur',
            'accessibility' => null,
        ),
        'profile' => array(
            'view' => '_profile', 'icon' => null,
            'title' => 'Profile', 'accessibility' => null,
        ),
        'notif' => array(
            'view' => '_notif', 'icon' => null,
            'title' => 'Notifications', 'accessibility' => null,
        ),
        'about' => array(
            'view' => '_about', 'icon' => null,
            'title' => 'A propos', 'accessibility' => null,
        ),
    );

    private $accessiblesById = array(
        // 'id_view' => array('view', 'icon', 'title', 'accessibility')
        'home' => array(
            'view' => '_home', 'title' => 'Acceuil',
            'icon' => 'fas fa-home',
            'accessibility' => '*',
        ),
        'eval' => array(
            'view' => '_general', 'title' => 'Evaluations',
            'icon' => 'fas fa-calculator',
            'accessibility' => array(
                Role::ROLE_STUDENT,
                Role::ROLE_DELEGATE,
                Role::ROLE_PROFESSOR,
                Role::ROLE_DIRECTOR
            ),
        ),
        'pres' => array(
            'view' => '_general', 'title' => 'Assiduité aux cours',
            'icon' => 'fas fa-calendar-alt',
            'accessibility' => array(
                Role::ROLE_STUDENT,
                Role::ROLE_DELEGATE,
                Role::ROLE_PROFESSOR,
                Role::ROLE_INSPECTOR,
                Role::ROLE_DIRECTOR
            ),
        ),
        'progress' => array(
            'view' => '_general', 'title' => 'Progression des cours',
            'icon' => 'fas fa-tasks',
            'accessibility' => array(
                Role::ROLE_STUDENT,
                Role::ROLE_DELEGATE,
                Role::ROLE_PROFESSOR,
                Role::ROLE_INSPECTOR,
                Role::ROLE_DIRECTOR
            ),
        ),
        'seance' => array(
            'view' => '_general', 'title' => 'Séance',
            'icon' => 'fas fa-calendar-plus',
            'accessibility' => array(
                Role::ROLE_DELEGATE,
                Role::ROLE_PROFESSOR,
                Role::ROLE_INSPECTOR,
                Role::ROLE_DIRECTOR
            ),
        ),
        'classes' => array(
            'view' => '_general', 'title' => 'Gestion des classes',
            'icon' => 'fas fa-users',
            'accessibility' => array(
                Role::ROLE_DIRECTOR
            ),
        ),
        'maquettes' => array(
            'view' => '_general', 'title' => 'Organisation des maquettes',
            'icon' => 'fas fa-users-cog',
            'accessibility' => array(
                Role::ROLE_DIRECTOR
            ),
        ),
        'admin' => array(
            'view' => '_general', 'title' => 'Administration',
            'icon' => 'fas fa-wrench',
            'accessibility' => array(
                Role::ROLE_DIRECTOR
            ),
        ),
    );


    //Actions
    public function retrieveMenu(Request $request) {
        //$userStatusList = User::find(Auth::user()-)->castStatusToArray();
        $userRolesList = User::find($request->user()->id)->allRoles();
        $menu = array();
        foreach ($this->accessiblesById as $key => $value) {
            if ( $this->isAccessibleByRoles( $userRolesList, $value['accessibility'] ) ) {
                array_push( $menu, (object) array(
                    'idView' => $key,
                    'icon' => $value['icon'],
                    'title' => $value['title'],
                ) );
            }
        }
        return $menu;
    }
    public function isAccessibleByRoles($userRolesList, $accessList) {
        if ($accessList == '*') {
            return true;
        }
        if ( gettype($accessList) == "array" && array_intersect($userRolesList, $accessList) ) {
            return true;
        }
        return false;
    }
    public function retrieveMiddleView(Request $request) {
        $idView = $this->retrieveIdView($request);
        if ( array_key_exists($idView, $this->accessiblesById) ) {
            return $this->accessiblesById[$idView]['view'];;
        } else if ( array_key_exists($idView, $this->accessiblesAllways) ) {
            return $this->accessiblesAllways[$idView]['view'];
        }
        return null;
    }
    public function retrieveIdView(Request $request) {
        $idView = $request->session()->get('idView') ?? $this->specialIds['default'];
        if (
            !array_key_exists($idView, $this->accessiblesById) &&
            !array_key_exists($idView, $this->accessiblesAllways)
        ) {
            $idView = $this->specialIds['error'];
        }
        $request->session()->put('idView', $idView);
        return $idView;
    }

    public function getCountNotif(Request $request) {
        return $request->user()->getCountNotif();
    }

    public function getPseudo(Request $request) {
        return $request->user()->matricule;
    }

    public function getProfile(Request $request) {
        $currentuser = User::find(Auth::user()->id);
        //$currentuser = User::find($request->user()->id);
        return $currentuser ? $currentuser->getProfile() : null;
    }
}
