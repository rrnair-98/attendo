<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>'forceSSL'], function (){
    \Illuminate\Support\Facades\Auth::routes();
});

Route::get('/', function () {
    return view('auth.login');
});
Route::get("/home", function (){
    return view("dashboard");
});
