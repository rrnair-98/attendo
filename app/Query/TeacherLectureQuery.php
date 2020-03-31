<?php


namespace App\Query;


use App\TeacherLecture;

class TeacherLectureQuery
{

    /**
     * Fetches a teacher lecture instance by teacher and lecture id
     * @param $teacherId
     * @param $lectureId
     * @return mixed
     */
    public function findByTeacherAndLectureId($teacherId, $lectureId){
        return TeacherLecture::where('user_id', $teacherId)->where('lecture_id', $lectureId)
            ->firstOrFail();
    }

    /**
     * Fetches a lecture names, lecture id and teacher lecture id with the specified teacher id
     * @param $teacherId ID id of the teacher
     * @param int $limit Number of records to fetch
     * @param int $offset Number of records to skip
     * @return mixed
     */
    public function findAllByTeacherIdJoinedWithLectured($teacherId, $limit=15, $offset=0){
        $limit = $limit & 0xf;
        return TeacherLecture::join('lectures', 'lectures.id', '=', 'teacher_lectures.lecture_id')
            ->where('teacher_lectures.user_id', $teacherId)
            ->select('lectures.*', 'teacher_lecture_id as actual_relation_id')
            ->get();
    }

    /**
     * Returns a list of lectures with the given teacher lecture ids
     * @param array $ids
     * @return mixed
     */
    public function findAllByIdWithLectures(array $ids){
        return TeacherLecture::whereIn("teacher_lectures.id", $ids)->join('lectures', 'lectures.id', '=', 'teacher_lectures.lecture_id')
            ->select("lectures.id", "lectures.lecture_name as name", "teacher_lectures.id as teacher_lecture_id")
            ->get();
    }

}
