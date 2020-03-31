<?php


namespace App\Http\Controllers;


use App\Exports\AttendanceExporter;
use App\Helpers\ResponseHelper;
use App\Query\TeacherLectureQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelDownloadController
{
    private $teacherLecturQuery;
    public function __construct(TeacherLectureQuery $teacherLectureQuery)
    {
        $this->teacherLecturQuery = $teacherLectureQuery;
    }

    public function downloadAttendanceReport(Request $request){
        $requestBody = $request->all();
        if(array_key_exists("teacher_lecture_ids", $requestBody)){
            $export = new AttendanceExporter($this->teacherLecturQuery);
            $export->withTeacherLectureIds($requestBody["teacher_lecture_ids"]);
            return $export->download(Carbon::now()->format("Y-m-d")."attendance.xls");
        }
        return ResponseHelper::badRequest("expected key teacher_lecture_ids");
    }

    public function test(){
        $export = new AttendanceExporter($this->teacherLecturQuery);
        $export->withTeacherLectureIds([1,2]);
        return $export->download(Carbon::now()->format("Y-m-d")."attendance.xls");
    }
}
