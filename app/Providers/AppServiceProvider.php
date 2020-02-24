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

        /**
         * Services
         */
        \App\Services\TokenService::class=>\App\Services\TokenService::class,

        /**
         * Mutators
         */


        /**
         * Transactors
         */
        \App\Transactors\AuthTransactor::class=> \App\Transactors\AuthTransactor::class,

    ];
}
