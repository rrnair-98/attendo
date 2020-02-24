<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected $singleton = [
        /**
         * Queries
         */
        \App\Query\UserQuery::class => \App\Query\UserQuery::class,
        \App\Query\TeacherLectureQuery::class=>\App\Query\TeacherLectureQuery::class,
        /**
         * Services
         */
        \App\Services\TokenService::class=>\App\Services\TokenService::class,

        /**
         * Mutators
         */
        \App\Transactors\Mutations\AttendanceTokenMutator::class=>\App\Transactors\Mutations\AttendanceTokenMutator::class,
        \App\Transactors\Mutations\TeacherLectureMutator::class=>\App\Transactors\Mutations\TeacherLectureMutator::class,
        \App\Transactors\Mutations\ClassLectureMutator::class=>\App\Transactors\Mutations\ClassLectureMutator::class,
        /**
         * Transactors
         */
        \App\Transactors\AuthTransactor::class=> \App\Transactors\AuthTransactor::class,
        \App\Transactors\AttendanceTokenTransactor::class=>\App\Transactors\AttendanceTokenTransactor::class,
    ];
}
