<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // insertions are made for every attendance the teacher pushes.
        // this way you can get total lecture count also
        Schema::create('class_lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_lecture_id');// a teacher lecture id
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->longText('description')->nullable(); // a JSON col that could be used later to store what topics the teacher covered
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_lectures');
    }
}
