<?php

use Illuminate\Database\Seeder;

class ClassLectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        //
        foreach (\App\TeacherLecture::all() as $teacherLecture){
            $i=-1;
            while(++$i < 10){
                $classLecture = \App\ClassLecture::create(['teacher_lecture_id'=> $teacherLecture->id, 'created_by'=>$teacherLecture->user_id,
                    'description'=>$faker->title]);
                foreach (\App\StudentLecture::where('teacher_lecture_id', $teacherLecture->id)->inRandomOrder()->take(10)->get() as $subbedStudent){
                    \App\AttendanceToken::create([
                        'token' => hash('sha256', \Carbon\Carbon::now()->timestamp.$subbedStudent->user_id.$subbedStudent->id
                            .$teacherLecture->id.$teacherLecture->user_id.$classLecture->id),
                        'expires_at'    => \Carbon\Carbon::now()->addHours(\App\AttendanceToken::MAX_EXPIRY_IN_MINUTES),
                        'created_by'    => $subbedStudent->user_id,
                        'is_present'    => 1,
                        'class_lecture_id'=> $classLecture->id,
                        'updated_by'    => $teacherLecture->user_id
                    ]);
                }
            }
        }
    }
}
