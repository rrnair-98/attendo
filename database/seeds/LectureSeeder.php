<?php

use Illuminate\Database\Seeder;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $i =-1;
        while(++$i <= 7) {
            \Illuminate\Support\Facades\DB::table('lectures')->insert([
                'teacher_id' => 1,
                'day_of_week' => $i,
                'department_id'=>1,
                'lecture_number'=>1,
                'subject_name'=>\Illuminate\Support\Str::random(),
                'created_by'=>0
            ]);
        }
    }
}
