<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            //Absence
            $table->bigIncrements('id');
            $table->unsignedBigInteger('seance_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->json('period')->nullable();
            $table->timestamps();

            //Indexes
            $table->foreign('seance_id')->references('id')->on('seances');
            $table->foreign('student_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absences');
    }
}
