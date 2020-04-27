<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricule')->unique();
            $table->string('email')->unique();
            $table->string('nom')->nullable();
            $table->string('prenoms')->nullable();
            $table->string('password');
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->unsignedTinyInteger('title_id')->nullable(); //index
            $table->unsignedTinyInteger('status_id')->nullable(); //index - state_activation
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

//            $table->string('civilite', ['M.', 'Mlle', 'Mme', 'Dr', 'Pr', 'Me'])->nullable();
//            $table->set('status', ['student', 'professor/teacher', 'director', 'inspector', 'admin'])->nullable();
            //Indexes
            $table->foreign('title_id')->references('id')->on('titles');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
