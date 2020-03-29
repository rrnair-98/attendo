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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::get('hello/', function(Request $request){
    return json_encode(['message' =>'hello'] );
});
Route::post('login', "AuthController@login");
Route::post('refresh/{refreshToken}', 'AuthController@refresh');
Route::middleware('token')->group(function (){
    Route::delete('logout', 'AuthController@logout');
    Route::get('user/', function (Request $request){
        return $request->user();
    });

    // todo wrap this in student middleware
    Route::post('student/attendance/token', 'AttendanceTokenController@createAttendanceToken');
    Route::get('student/attendance/avg', 'AttendanceTokenController@');
    // todo wrap in teacher, hod middleware
    Route::post('attendance/{teacherLectureId}', 'AttendanceTokenController@markStudentsPresent');
  /*  Route::get('attendance/class-lecture/{classLectureId}', 'AttendanceToken@getAllStudentAttendanceForClassLectureId');
    Route::get('attendance/teacher-lecture/{teacherLectureId}', 'AttendanceToken@getAllStudentAttendanceForTeacherLectureId');*/

});

/**
 * TODO - APPLY middleware to these methods.
 */

Route::get('attendance/student/{start}/{end}/{studentId}/', 'AttendanceController@getByStudentId');
Route::get('attendance/lecture/{start}/{end}/{lectureId}/', 'AttendanceController@getByLectureId');
Route::get('attendance/student-and-lecture/{start}/{end}/{studentId}/{lectureId}/', 'AttendanceController@getByStudentAndLectureId');
Route::post('attendance/{teacherId}/{lectureId}/', 'AttendanceController@postBulkInsertAttendance')->middleware('token', 'teacher');
Route::resource('attendance', 'AttendanceController');

