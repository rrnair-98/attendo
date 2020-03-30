<?php

namespace App\Http\Controllers;

use App\Query\StudentLectureQuery;
use App\Query\TeacherLectureQuery;
use App\Services\LectureService;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    //
    private $studentLectureQuery;
    private $teacherLectureQuery;
    public function __construct(StudentLectureQuery $query, TeacherLectureQuery $teacherLectureQuery)
    {
        $this->studentLectureQuery = $query;
        $this->teacherLectureQuery = $teacherLectureQuery;
    }

    public function getLecturesForTeacher(Request $request){
        return response($this->teacherLectureQuery->findAllByTeacherIdJoinedWithLectured($request->user->id));
    }

    public function getLecturesForStudent(Request $request){
        return response($this->studentLectureQuery->getLecturesOnlyByStudentId($request->user->id));
    }

}
