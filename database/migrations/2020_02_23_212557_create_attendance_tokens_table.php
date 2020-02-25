<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('is_present')->default(0); // set by the teacher when he she posts attendance tokens.
            $table->unsignedBigInteger('class_lecture_id'); // set by the teacher who posts tokens.
            $table->string('token', 255)->unique();// a unique token for this lecture
            $table->dateTime('expires_at');
            $table->unsignedBigInteger('created_by'); // id of the student
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('attendance_tokens');
    }
}
