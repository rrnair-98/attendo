<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceQueryController extends Controller
{
    //
    private $attendanceService;
    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getIndex(){
        return response("hello world");
    }

    public function getByLectureId(string $start, string $end, int $lectureId){
        return response($this->attendanceService->attendanceByLectureId(Carbon::parse($start), Carbon::parse($end), $lectureId));
    }

    public function getByStudentId(string $start, string $end, int $studentId){
        return response($this->attendanceService->attendanceByStudentId(Carbon::parse($start), Carbon::parse($end), $studentId));
    }

    public function getByStudentAndLectureId(string $start, string $end, int $studentId, int $lectureId){
        return response($this->attendanceService->attendanceByStudentAndLecture(Carbon::parse($start), Carbon::parse($end), $studentId, $lectureId));
    }



}
