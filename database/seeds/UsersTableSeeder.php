<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
        $faker = Faker\Factory::create();
        while(++$i <= 3) {
            \Illuminate\Support\Facades\DB::table('users')->insert(
                [
                    'email' => $faker->unique()->email,
                    'password' => 'temp',
                    'department_id' => 1,
                    'role' => \App\User::ROLE_TEACHER,
                    'name' => \Illuminate\Support\Str::random()
                ]
            );
            \Illuminate\Support\Facades\DB::table('users')->insert(
                [
                    'email' => $faker->unique()->email,
                    'password' => 'temp',
                    'department_id' => 1,
                    'role' => \App\User::ROLE_STUDENT,
                    'name' => \Illuminate\Support\Str::random()
                ]
            );
            \Illuminate\Support\Facades\DB::table('users')->insert(
                [
                    'email' => $faker->unique()->email,
                    'password' => 'temp',
                    'department_id' => 1,
                    'role' => \App\User::ROLE_HOD,
                    'name' => \Illuminate\Support\Str::random()
                ]
            );
        }
    }
}
