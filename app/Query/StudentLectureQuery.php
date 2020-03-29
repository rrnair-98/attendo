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
}
