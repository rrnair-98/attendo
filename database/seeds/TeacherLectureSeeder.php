<?php

use Illuminate\Database\Seeder;

class TeacherLectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (\App\Lecture::all() as $lecture){
            \App\TeacherLecture::insert(
                [
                    'user_id' => \App\User::where('role', \App\User::ROLE_TEACHER)->inRandomOrder()->first()->id,
                    'lecture_id'    => $lecture->id,
                    'created_by'    => \App\User::where('email', 'anton@gmail.com')->first()->id,
                ]
            );
        }
        \App\TeacherLecture::insert(
            [
                'user_id' => \App\User::where('role', \App\User::ROLE_TEACHER)->inRandomOrder()->first()->id,
                'lecture_id'    => \App\TeacherLecture::find(1)->lecture_id,
                'created_by'    => \App\User::where('email', 'anton@gmail.com')->first()->id,
            ]
        );

    }
}
