<?php

use App\Models\Classe;
use App\Models\Course;
use App\Models\Ecue;
use App\Models\Filiere;
use App\Models\Note;
use App\Models\NoteStudent;
use App\Models\Progression;
use App\Models\Role;
use App\Models\Seance;
use App\Models\Status;
use App\Models\Syllabus;
use App\Models\Title;
use App\Models\Ue;
use App\Models\User;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Système - Compte spécial
        $this->userFactory('CREATE', 1, null, [
            'matricule' => 'system',
            'email' => 'system.lmdsoft@inphb.ci',
            'nom' => 'Système',
            'prenoms' => null,
            'status_id' => Status::where('label', 'test')->first()->id,
            'title_id' => Title::all()->random(1)->first()->id,
        ]);
        //Cyprax - Compte spécial
        $this->userFactory('CREATE', 1, '*', [
            'matricule' => '15INP00767',
            'email' => 'dev.cyprax@gmail.com',
            'nom' => 'KOUAKOU',
            'prenoms' => 'Blaih-Lacien Cyriaque',
            'status_id' => Status::where('label', 'test')->first()->id,
            'title_id' => null,
        ]);

        $this->userFactory('CREATE', 3, 'director');
        $this->userFactory('CREATE', 3, 'inspector');
        $this->userFactory('CREATE', 25, 'professor');

        factory(Filiere::class, 4)->create([
            'inspector_id' => Role::where('label', 'inspector')
                ->first()->users()->get()->random(1)->first()->id,
            'director_id' => Role::where('label', 'director')
                ->first()->users()->get()->random(1)->first()->id,
        ]);
        Filiere::all()->each(
            function (Filiere $filiere) {
                $filiere->classes()->saveMany(
                    factory(Classe::class, rand(2, 3))->create()
                );
            }
        );
        Classe::all()->each(
            function (Classe $classe) {
                $classe->students()->attach(
                    $this->userFactory('CREATE', rand(10, 15), 'student')
                        ->keyBy('id')->keys()->all()
                );
            }
        );
        // Groups ???
        //After Grouping ???

        factory(Ue::class, 20)->create();
        Ue::all()->each(
            function (Ue $ue) {
                $ue->ecues()->saveMany(
                    factory(Ecue::class, rand(1, 3))->create()
                );
            }
        );
        Ecue::all()->each(
            function (Ecue $ecue) {
                $ecue->syllabus()->save(
                    factory(Syllabus::class)->create()
                );
            }
        );

        //Courses (nogrouped) {Seance, Progression, Note}
        //factory(Course::class, 20)->state('nogrouped')->create();
        factory(Course::class, 20)->create();
        Course::all()->each(
            function (Course $course) {
                $course->seances()->saveMany(
                    factory(Seance::class, rand(3, 7))->create()
                );
                $course->notes()->saveMany(
                    factory(Note::class, rand(2, 5))->create()
                );
            }
        );
        Seance::all()->each(
            function (Seance $seance) {
                $seance->progression()->save(
                    factory(Progression::class)->create()
                );
            }
        );
        Note::all()->each(
            function (Note $note) {
                $keysArr = $note->course()->get()->first()->coursable()->get()->first()->students()->get()->keyBy('id')->keys()->all();
                $studentsArr = [];
                foreach ($keysArr as $key) {
                    $studentsArr[$key] = [
                        'value' => factory(NoteStudent::class)->make()->value
                    ];
                }
                $note->students()->attach($studentsArr);
            }
        );
    }

    public function userFactory($method = 'CREATE', $number = 1, $roleLabel = null, $arrayParams = null)
    {
        $users = null;
        switch (strtoupper($method)) {
            case 'CREATE':
                $users = factory(User::class, $number)->create($arrayParams ?? []);
                if ($roleLabel) {
                    $roleId = $roleLabel == '*' ?
                        Role::all()->keyBy('id')->keys()->all() :
                        Role::where('label', $roleLabel)->first()->id;
                    foreach ($users->all() as $user) {
                        $user->roles()->attach($roleId);
                    };
                }
                break;
            case 'MAKE':
                //In that case, we can't attach it to anything
                $users = factory(User::class, $number)->make($arrayParams ?? []);
                break;
            default:
                //$users still be null
                break;
        }
        return $users;
    }
}
