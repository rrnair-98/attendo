<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('attendance/student/{start}/{end}/{studentId}/', 'AttendanceQueryController@getByStudentId');
Route::get('attendance/lecture/{start}/{end}/{lectureId}/', 'AttendanceQueryController@getByLectureId');
Route::get('attendance/student-and-lecture/{start}/{end}/{studentId}/{lectureId}/', 'AttendanceQueryController@getByStudentAndLectureId');
Route::resource('attendance', 'AttendanceQueryController');
