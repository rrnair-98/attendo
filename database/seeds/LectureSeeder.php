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
        $faker = \Faker\Factory::create();
        while(++$i <= 7) {
            \App\Lecture::insert([
                'lecture_name' => $faker->title,
                'created_by'    => \App\User::where("email", 'anton@gmail.com')->first()->id
            ]);
        }


    }
}
