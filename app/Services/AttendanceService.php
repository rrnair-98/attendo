<?php


namespace App\Services;


use App\Attendance;
use App\Services\AttendanceTokenService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceService
{


    public static $ALL_STUDENT_ATT_BW_DATES_QUERY = "
        select
            att.student_id, (att.lecs_attended/ lecs.num * 100) as percentage , att.lecture_id, att.lecs_attended, lecs.num,
            lectures.subject_name as lecture_name
        from
            (select student_id, count(student_id)lecs_attended, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by lecture_id) lecs
        inner join
            lectures
        where
            lectures.id = att.lecture_id and
            lecs.lecture_id = att.lecture_id";

    // since you cant reuse constants to define other constants during compilation
    public static $STUDENT_ATT_BY_ID = "
        select
            att.student_id, (att.lecs_attended/ lecs.num * 100) as percentage , att.lecture_id, att.lecs_attended, lecs.num,
            lectures.subject_name as lecture_name
        from
            (select student_id, count(student_id)lecs_attended, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by lecture_id) lecs
        inner join
            lectures
        where
            lectures.id = att.lecture_id and
            lecs.lecture_id = att.lecture_id and
            att.student_id = %s";


    // works for teachers too since teachers are bound to lectures
    public static $ATT_BY_LECTURE_ID = "
        select
            att.student_id, (att.lecs_attended/ lecs.num * 100) as percentage , att.lecture_id, att.lecs_attended, lecs.num,
            lectures.subject_name as lecture_name
        from
            (select student_id, count(student_id)lecs_attended, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by lecture_id) lecs
        inner join
            lectures
        where
            lectures.id = att.lecture_id and
            lecs.lecture_id = att.lecture_id and
            att.lecture_id = %s";



    public static $ATT_BY_LECTURE_ID_AND_STUDENT_ID = "
        select
            att.student_id, (att.lecs_attended/ lecs.num * 100) as percentage , att.lecture_id, att.lecs_attended, lecs.num,
            lectures.subject_name as lecture_name
        from
            (select student_id, count(student_id)lecs_attended, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where created_at >= '%s' and created_at <='%s'
                        group by lecture_id) lecs
        inner join
            lectures
        where
            lectures.id = att.lecture_id and
            lecs.lecture_id = att.lecture_id and
            att.lecture_id = %s and
            att.student_id = %s";


    public function getById(int $id): Attendance{
        return Attendance::find($id);
    }

    public function bulkInsert(int $createdById, array $args)
    {
        $data = $this->getValidArgsWithDates($args);
        DB::beginTransaction();
        DB::table($this->tableName)->insert($data);
        DB::commit();
    }

    /**
     * Returns attendance of all students in all subjects.
     */
    public function attendanceBetweenDates(Carbon $date1, Carbon $date2){
        return DB::select(sprintf(AttendanceService::$ALL_STUDENT_ATT_BW_DATES_QUERY, $date1, $date2, $date1 , $date2));
    }

    /**
     * Gets attendance of students for a particular lecture
     * @param Carbon $start
     * @param Carbon $end
     * @param int $lectureId
     * @return array
     */
    public function attendanceByLectureId(Carbon $start, Carbon $end, int $lectureId){
        return DB::select(sprintf(AttendanceService::$ATT_BY_LECTURE_ID, $start, $end, $start, $end, $lectureId));
    }

    /**
     * Retrieves attendance of student given studentId
     * @param Carbon $start
     * @param Carbon $end
     * @param int $studentId
     * @return array
     */
    public function attendanceByStudentId(Carbon $start, Carbon $end, int $studentId){
        return DB::select(sprintf(AttendanceService::$STUDENT_ATT_BY_ID, $start, $end, $start, $end, $studentId));
    }

    /**
     * Retrieves attendance of student for specified lecture and studentId.
     * @param Carbon $start
     * @param Carbon $end
     * @param int $studentId
     * @param int $lectureId
     * @return array
     */
    public function attendanceByStudentAndLecture(Carbon $start, Carbon $end, int $studentId, int $lectureId){
        return DB::select(sprintf(AttendanceService::$ATT_BY_LECTURE_ID_AND_STUDENT_ID, $start, $end, $start, $end,
            $lectureId, $studentId));
    }


    private function getValidArgsWithDates(array $args) : array {
        $validData = array();
        $tempList = array();
        $teacherId = $args[0]['teacher_id'];
        $lecture_id = $args[0]['lecture_id'];
        $i = 0;
        foreach ($args as $arg){
            $tempList[$i++] = $arg['student_token'];
        }

        $i = 0;
        $validStudents = $this->attendanceToken->getValidStudentData($tempList);
        foreach ($validStudents as &$valid){
            $validData[$i]["created_at"] = Carbon::now();
            $valid[$i]["teacher_id"] = $teacherId;
            $valid[$i]["lecture_id"] = $lecture_id;
            ++$i;
        }

        return $validData;

    }






}
