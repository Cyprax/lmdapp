<?php

namespace App\Http\Traits;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;

trait AppUserControls {

    private $translateRolesArray = array(
        Role::ROLE_DEFAULT => 'Indéfini',
        Role::ROLE_STUDENT => array(
            'M' => 'Etudiant',
            'F' => 'Etudiante',
            'default' => 'Etudiant'
        ),
        Role::ROLE_DELEGATE => array(
            'M' => 'Délégué',
            'F' => 'Déléguée',
            'default' => 'Délégué'
        ),
        Role::ROLE_ADMIN => array(
            'M' => 'Administrateur',
            'F' => 'Administrateur',
            'default' => 'Etudiant'
        ),
        Role::ROLE_PROFESSOR => array(
            'M' => 'Professeur',
            'F' => 'Professeur',
            'default' => 'Professeur'
        ),
        Role::ROLE_DIRECTOR => array(
            'M' => 'Directeur',
            'F' => 'Directrice',
            'default' => 'Directeur'
        ),
        Role::ROLE_INSPECTOR => array(
            'M' => 'Inspecteur',
            'F' => 'Inspectrice',
            'default' => 'Inspecteur'
        )
    );


    //Functions
    public function translateRoles() {
        $rolesArray = $this->allRoles();
        $tmpArray = array();
        foreach ($rolesArray as $value) {
            if( isset( $this->translateStatusArray[$value] ) ) {
                array_push($tmpArray, $this->translateRolesArray[$value][$this->gender] ??
                                        $this->translateRolesArray[$value][Role::ROLE_DEFAULT]);
            }
            else {
                array_push($tmpArray, $this->translateRolesArray[Role::ROLE_DEFAULT]);
            }
        }
        return implode(" - ", $tmpArray);
    }

    /** @return array */
    public function basicRoles() {
        return $this->roles()->get()->map->name->toArray();
    }

    /** @return array */
    public function allRoles() {
        $allRoles = $this->basicRoles();
        if ( $this->isDelegated() ) {
            array_push($allRoles, Role::ROLE_DELEGATE);
        }
        return $allRoles;
    }

    /** @return string */
    public function strRoles() {
        $collectionRoles = collect( $this->allRoles() );
        return $collectionRoles->join(', ', ' & ');
    }

    /** @return array */
    public function getProfile() {
        return array(
            'matricule' => strtoupper($this->matricule),
            'nom' => strtoupper($this->nom),
            'prenoms' => ucwords( strtolower($this->prenoms) ),
            'gender' => strtoupper($this->gender),
            'email' => strtolower($this->email),
            'roles' => ucwords( strtolower( $this->strRoles() ) ),
            'countNotif' => $this->getCountNotif(),
        );
    }

    /** @return string */
    public function getFullName() {
        $nom = strtoupper($this->nom);
        $prenoms = ucwords( strtolower($this->prenoms) );
        return $nom . ' ' . $prenoms;
    }

    /** @return integer */
    public function getCountNotif() { //??? Gestion des notifications?
        //return $this->notifications(Notification::NOTIF_FROM)->count();
        return 25;
    }

}
