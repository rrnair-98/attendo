<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Query\AttendanceTokenQuery;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;

class AttendanceController extends Controller
{
    //
    private $attendanceQuery;
    public function __construct(AttendanceTokenQuery $attendanceTokenQuery)
    {
        $this->attendanceQuery = $attendanceTokenQuery;
    }

    public function getIndex(){
        return response("hello world");
    }

    public function getAttendanceForStudentByStudentLectureId($studentLectureId, Request $request){
        try {
            return response($this->attendanceQuery->getStudentAttendanceByStudentIdAndStudentLectureId($request->user->id, $studentLectureId));
        } catch (ModelNotFoundException $e){
            return ResponseHelper::notFound("studnetLecture");
        }
    }

    public function getAttendanceForTeacherByTeacherLectureId($teacherLectureId){
        try{
            return $this->attendanceQuery->getStudentAttendanceByTeacherLectureId($teacherLectureId);
        } catch (ModelNotFoundException $e){
            return ResponseHelper::notFound("teacher lecture");
        }
    }

}
