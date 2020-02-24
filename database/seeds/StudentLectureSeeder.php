<?php

use Illuminate\Database\Seeder;

class StudentLectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $arr = array();
        $studentCount = \App\User::where('role', \App\User::ROLE_STUDENT)->count();
        foreach (\App\TeacherLecture::take(5)->get() as $lecture){
            foreach(\App\User::where('role', \App\User::ROLE_STUDENT)->get() as $student){
                array_push($arr, ['user_id'=>$student->id, 'teacher_lecture_id'=>$lecture->id,
                'created_by'    => \App\User::where('email', 'anton@gmail.com')->first()->id]);
            }
        }

        foreach (\App\TeacherLecture::skip(5)->take(5)->get() as $lecture){
            foreach (\App\User::where('role', \App\User::ROLE_STUDENT)->inRandomOrder()->take($studentCount>>1)->get() as $student){
                array_push($arr, ['user_id'=>$student->id, 'teacher_lecture_id'=>$lecture->id,
                    'created_by'    => \App\User::where('email', 'anton@gmail.com')->first()->id]);
            }
        }
        \App\StudentLecture::insert($arr);

    }
}
