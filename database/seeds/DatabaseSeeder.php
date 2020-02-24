<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(LectureSeeder::class);
         $this->call(TeacherLectureSeeder::class);
         $this->call(StudentLectureSeeder::class);
    }
}
