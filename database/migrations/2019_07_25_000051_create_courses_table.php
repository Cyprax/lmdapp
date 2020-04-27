<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('grouped')->default(false);
            $table->unsignedBigInteger('professor_id')->nullable();
            $table->unsignedBigInteger('ecue_id')->nullable();
            $table->decimal('coefficient', 3, 2); //Exple: 003.50
            $table->unsignedTinyInteger('course_state_id')->nullable();
            $table->unsignedTinyInteger('semester_id')->nullable();
            $table->unsignedBigInteger('coursable_id')->index();
            $table->string('coursable_type');

            $table->timestamps();

            //Indexes
            $table->foreign('professor_id')->references('id')->on('users');
            $table->foreign('ecue_id')->references('id')->on('ecues');
            $table->foreign('course_state_id')->references('id')->on('course_states');
            $table->foreign('semester_id')->references('id')->on('semesters');
                //coursable_id ->{classes.id, groups.id}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
