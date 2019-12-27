<?php


namespace App\Services;


use App\Lecture;
use phpDocumentor\Reflection\Types\Array_;

class LectureService extends ServiceBase
{
    public function __construct()
    {
        $this->tableName = "lectures";
    }

    /**
     * Finds and returns a Lecture instance by id.
     * Returns null if the id is absent.
     * @param int $id ID of the lecture to be found
     * @return Lecture
     */
    public function findById(int $id){
        return Lecture::find($id);
    }

    /**
     * Returns a list of lectures that are bound to provided teacherId
     * @param int $teacherId
     * @return Array_[Lecture[
     */
    public function findByTeacherId(int $teacherId){
        return Lecture::where('teacher_id', '=', $teacherId)->get();
    }

    /**
     * Returns a list of Lecture that are bound to this teacher and depertment
     * @param int $teacherId
     * @param int $departmentId
     * @return mixed
     */
    public function findByTeacherAndDepartment(int $teacherId, int $departmentId){
        return Lecture::where('teacher_id', '=', $teacherId)->where('department_id', '=', $departmentId)->get();
    }

    public function findAllByDepartment(int $departmentId){
        return Lecture::where('department_id', '=', $departmentId)
            ->orderBy('day_of_week', 'asc')->orderBy('lecture_number', 'asc')->get();
    }

    /**
     * Returns a list of lectures found by dayOfWeek and departmentId
     * @param int $dayOfweek
     * @param int $departmentId
     * @return mixed
     */
    public function findAllByDepartmentAndDayOfWeek(int $dayOfweek, int  $departmentId){
        return Lecture::where('department_id', '=', $departmentId)->where('day_of_week', '=', $dayOfweek)
            ->orderBy('day_of_week', 'asc')->orderBy('lecture_number', 'asc')->get();
    }


}
