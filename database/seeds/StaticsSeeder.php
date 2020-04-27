<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Basics
        //Role
        DB::table('roles')->insert([
            ['label' => 'student', 'description' => 'Etudiant(e)'],
            ['label' => 'professor', 'description' => 'Professeur'],
            ['label' => 'director', 'description' => 'Directeur(trice) des études'],
            ['label' => 'inspector', 'description' => 'Inspecteur(trice) de filière'],
            ['label' => 'admin', 'description' => 'Administrateur(trice)'],
        ]);
        //Semester
        DB::table('semesters')->insert([
            ['label' => 'sem1', 'description' => 'Semestre 1'],
            ['label' => 'sem2', 'description' => 'Semestre 2'],
            ['label' => 'sem3', 'description' => 'Semestre 3'],
            ['label' => 'sem4', 'description' => 'Semestre 4'],
            ['label' => 'sem5', 'description' => 'Semestre 5'],
            ['label' => 'sem6', 'description' => 'Semestre 6'],
            ['label' => 'sem7', 'description' => 'Semestre 7'],
            ['label' => 'sem8', 'description' => 'Semestre 8'],
            ['label' => 'sem9', 'description' => 'Semestre 9'],
            ['label' => 'sem10', 'description' => 'Semestre 10'],
        ]);
        //GroupState
        DB::table('group_states')->insert([
            ['label' => 'classe', 'description' => 'Groupe de classe'],
            ['label' => 'filiere', 'description' => 'Groupe de filière'],
            ['label' => 'promo', 'description' => 'Groupe de promotion'],
        ]);
        //Status
        DB::table('statuses')->insert([
            ['label' => 'inactive', 'description' => 'Compte inactif'],
            ['label' => 'active', 'description' => 'Compte actif'],
            ['label' => 'bloqued', 'description' => 'Compte bloqué'],
            ['label' => 'ended', 'description' => 'Compte étudiant - Fin de parcours'],
            ['label' => 'retreat', 'description' => 'Compte professionel - En retraite'],
            ['label' => 'test', 'description' => 'Compte test'],
        ]);
        //Title
        DB::table('titles')->insert([
            ['label' => 'M.', 'description' => 'Monsieur'],
            ['label' => 'Mme', 'description' => 'Madame'],
            ['label' => 'Dr', 'description' => 'Docteur'],
            ['label' => 'Pr', 'description' => 'Professeur'],
            ['label' => 'Dir.', 'description' => 'Directeur'],
        ]);
        //CourseState
        DB::table('course_states')->insert([
            ['label' => 'active', 'description' => 'Enseignement actif'],
            ['label' => 'inactive', 'description' => 'Enseignement inactif'],
            ['label' => 'ended', 'description' => 'Enseignement terminé'],
            ['label' => 'bloqued', 'description' => 'Enseignement bloqué'],
            ['label' => 'test', 'description' => 'Enseignement test'],
        ]);
    }
}
