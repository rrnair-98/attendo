<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('role');//teacher, student or HOD
            $table->integer('department_id')->default(0);// for now comps, also holds the semester in the lsbs
            $table->tinyInteger('gender');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 10)->unique()->nullable();
            $table->string('password');
            $table->text('img_url')->nullable();
            $table->json('description')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
