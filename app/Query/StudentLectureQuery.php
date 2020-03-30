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
        return StudentLecture::join("lectures", "lectures.id", "=", "student_lectures.lecture_id")
            ->where("student_lectures.user_id", $studentId)
            ->select("lectures.*")
            ->get();
    }
}
