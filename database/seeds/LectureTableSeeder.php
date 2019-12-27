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

            ]
        );
    }
}
