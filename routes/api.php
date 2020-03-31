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
Route::middleware('token')->group(function (){
    Route::post('refresh/{refreshToken}', 'AuthController@refresh');
    Route::delete('logout', 'AuthController@logout');
    Route::get('user/', function (Request $request){
        return $request->user();
    });

    // todo wrap this in student middleware
    Route::get('student/attendance/avg', 'AttendanceTokenController@');

    Route::middleware("student", function(){
        Route::get("student/lectures", "LectureController@getLecturesForStudent");
        Route::post('student/attendance-token', 'AttendanceTokenController@createAttendanceToken');
        Route::get("student/attendance/{studentLectureId}", "AttendanceController@getAttendanceForStudentByStudentLectureId");

    });

    Route::middleware('teacher', function(){
        Route::get("teacher/student/att-token/{attendanceToken}", "AttendanceTokenController@getUserDetailsFromToken");
        Route::get("teacher/lectures", "LectureController@getLecturesForTeacher");
        Route::post('teacher/attendance/{teacherLectureId}', 'AttendanceTokenController@markStudentsPresent');
        Route::post("teacher/report", "ExcelDownloadController@downloadAttendanceReport");
    });

});
/*Route::get("test", "ExcelDownloadController@test");*/
