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
        $password = hash('sha256', "password");
        $og = \App\User::create([
            'email' => "anton@gmail.com",
            'password' => $password,
            'role' => \App\User::ROLE_ADMIN,
            'name' => "Anton Leme",
            'gender'    => rand(1,2),
            'created_by'=> 0
        ]);
        \App\User::create([
            'email' => "sonOfAnton@gmail.com",
            'password' => $password,
            'role' => \App\User::ROLE_ADMIN,
            'name' => "Son of Anton Leme",
            'gender'    => rand(1,2),
            'created_by'=> 0
        ]);
        while(++$i <= 5) {
            \App\User::create(
                [
                    'email' => $faker->unique()->email,
                    'password' => $password,
                    'role' => \App\User::ROLE_TEACHER,
                    'name' => $faker->name,
                    'gender' => rand(1, 2),
                    'created_by' => $og->id
                ]
            );
        }
        $i=-1;
        while(++$i < 60) {
            \App\User::create(
                [
                    'email' => $faker->unique()->email,
                    'password' => $password,
                    'role' => \App\User::ROLE_STUDENT,
                    'name' => $faker->name,
                    'gender' => rand(1, 2),
                    'created_by' => $og->id,
                    'roll_number'=>$i
                ]
            );
        }
        \App\User::create(
            [
                'email' => $faker->unique()->email,
                'password' => $password,
                'role' => \App\User::ROLE_HOD,
                'name' => $faker->name,
                'gender'    => rand(1,2),
                'created_by'=> $og->id
            ]
        );

    }

}
