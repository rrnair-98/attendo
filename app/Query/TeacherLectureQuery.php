<?php


namespace App\Query;


use App\TeacherLecture;

class TeacherLectureQuery
{
    public function findAllByTeacherId($teacherId, $limit=15, $offset=0){
        $limit = $limit & 0xf;
        return TeacherLecture::where('user_id', $teacherId)->skip($offset)->take($limit)
            ->get();
    }

    public function findByTeacherAndLectureId($teacherId, $lectureId){
        return TeacherLecture::where('user_id', $teacherId)->where('lecture_id', $lectureId)
            ->firstOrFail();
    }

}
