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
Route::get('hello/', function(Request $request){
    return json_encode(['message' =>'hello'] );
});
Route::post('login', "AuthController@login");
Route::post('refresh', 'AuthController@refresh');
Route::middleware('token')->group(function (){
    Route::delete('logout', 'AuthController@logout');
});


Route::post('attendanceToken/create', 'AttendanceTokenController@postCreateAttendanceToken')->middleware('token', 'student');

Route::get('lectures/department/{departmentId}', 'LectureController@getByDepartment');
Route::get('lectures/teacher/{teacherId}', 'LectureController@getByTeacher');

/**
 * TODO - APPLY middleware to these methods.
 */

Route::get('attendance/student/{start}/{end}/{studentId}/', 'AttendanceController@getByStudentId');
Route::get('attendance/lecture/{start}/{end}/{lectureId}/', 'AttendanceController@getByLectureId');
Route::get('attendance/student-and-lecture/{start}/{end}/{studentId}/{lectureId}/', 'AttendanceController@getByStudentAndLectureId');
Route::post('attendance/{teacherId}/{lectureId}/', 'AttendanceController@postBulkInsertAttendance')->middleware('token', 'teacher');
Route::resource('attendance', 'AttendanceController');

