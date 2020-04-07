<?php


namespace App\Query;


use App\StudentLecture;

class StudentLectureQuery
{
    public static function getAllByStudentId($studentId){
        return StudentLecture::where('user_id', $studentId)->with(['teacherLectures'])->get();
    }

    public static function getTeacherLectureIdsForStudent($studentId){
        return collect(self::getAllByStudentId($studentId))->map(function ($student){return $student->teacherLectures->id;})->toArray();
    }

    public function getLecturesOnlyByStudentId($studentId){
        return StudentLecture::join("teacher_lectures", "teacher_lectures.id", "=", "student_lectures.teacher_lecture_id")
            ->join("lectures", "lectures.id", '=', "teacher_lectures.lecture_id")
            ->where("student_lectures.user_id", $studentId)
            ->select("lectures.*", "student_lectures.id as actual_relation_id")
            ->get();
    }

}
