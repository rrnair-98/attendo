<?php

use Illuminate\Database\Seeder;

class AttendanceTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // fills data only for the student... ie cols class_lecture_id and is_present are default/0
        foreach (\App\TeacherLecture::all() as $teacherLecture){
            foreach (\App\StudentLecture::where('teacher_lecture_id', $teacherLecture->id)->get() as $subbedStudent){
                \App\AttendanceToken::create([
                    'token' => hash('sha256', \Carbon\Carbon::now()->timestamp.$subbedStudent->user_id.$subbedStudent->id),
                    'expires_at'    => \Carbon\Carbon::now()->addHours(\App\AttendanceToken::MAX_EXPIRY_IN_MINUTES),
                    'created_by'    => $subbedStudent->user_id,
                ]);
            }
        }
        $i=0;
        while(++$i<10) {
            \App\AttendanceToken::inRandomOrder()->skip(20)->take(10)->delete();
        }


    }
}
