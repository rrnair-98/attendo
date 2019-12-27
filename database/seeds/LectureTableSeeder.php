<?php

use Illuminate\Database\Seeder;

class LectureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $i=0;
        while(++$i <= 10)
        \Illuminate\Support\Facades\DB::table('lectures')->insert(
            [
                'teacher_id'=>1,
                'day_of_week'=>rand(1,6),
                'created_at'=>\Carbon\Carbon::now('ist'),
                'created_by'=>1,
                'department_id'=>1,
                'lecture_number'=>rand(1,9),
                'subject_name'=>\Illuminate\Support\Str::random()
            ]
        );
    }
}
