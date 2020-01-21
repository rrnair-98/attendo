<?php

use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $i = 0;
        while (++$i< 11) {

                $studentId = \App\User::where('role', '=', \App\User::ROLE_STUDENT)->inRandomOrder()->first()->id;

            $token = hash('sha256', \Carbon\Carbon::now()->timestamp.$studentId.$i);
            \Illuminate\Support\Facades\DB::table('attendances')->insert(
                [
                    'teacher_id' => \App\User::where('role', '=', \App\User::ROLE_TEACHER)->first()->id,
                    'lecture_id' => $i,
                    'student_id' => $studentId,
                    'student_token' =>$token,
                    'created_by_id' => 0,
                    'created_at' => \Carbon\Carbon::     now()
                ]
            );
            \Illuminate\Support\Facades\DB::table('attendance_tokens')->insert(
              [
                  'student_id' => $studentId,
                  'token' => $token,
                  'created_at' => \Carbon\Carbon::now()
              ]
            );
        }
    }
}
