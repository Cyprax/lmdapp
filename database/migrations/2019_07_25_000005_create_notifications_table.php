<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) { //???
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from'); //user_id
            $table->unsignedBigInteger('to'); //user_id
            $table->string('object');
            $table->text('message');
            $table->timestamps();
            //$table->engine = 'MyISAM'; //???? Needed?
            //Indexes -- FullSearchText
            $table->foreign('from')->references('id')->on('users');
            $table->foreign('to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
