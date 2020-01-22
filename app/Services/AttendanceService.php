<?php


namespace App\Services;


use App\Attendance;
use App\Services\AttendanceTokenService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceService
{

    private $attendanceTokenService;

    public static $ALL_STUDENT_ATT_BW_DATES_QUERY = "
        select
            att.student_id, (att.lecs_attended/ lecs.num * 100) as percentage , att.lecture_id, att.lecs_attended, lecs.num,
            lectures.subject_name as lecture_name
        from
            (select student_id, count(student_id)lecs_attended, lecture_id
                from attendances
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
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
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
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
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by lecture_id) lecs
        inner join
            lectures , users
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
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by student_id, lecture_id) att,
            (select count(lecture_id) as num, lecture_id
                from attendances
                    where CAST(created_at as DATE) between {d\"%s\"}  and {d\"%s\"}
                        group by lecture_id) lecs
        inner join
            lectures
        where
            lectures.id = att.lecture_id and
            lecs.lecture_id = att.lecture_id and
            att.lecture_id = %s and
            att.student_id = %s";


    public function __construct(AttendanceTokenService $tokenService)
    {
        $this->attendanceTokenService = $tokenService;
    }

    public function getById(int $id): Attendance{
        return Attendance::find($id);
    }

    public function bulkInsert(int $teacherId, int $lectureId, array $tokens)
    {
        $data = $this->attendanceTokenService->getValidStudentDataFromTokens($tokens, $teacherId, $lectureId);
        if(count($data)) {
            DB::beginTransaction();
            DB::table('attendances')->insert($data);
            DB::commit();
            return true;
        }
        return false;
    }

    /**
     * Returns attendance of all students in all subjects.
     */
    public function attendanceBetweenDates(Carbon $date1, Carbon $date2){
        return DB::select(sprintf(AttendanceService::$ALL_STUDENT_ATT_BW_DATES_QUERY, $date1->toDateString(), $date2->toDateString(), $date1->toDateString() , $date2->toDateString()));
    }

    /**
     * Gets attendance of students for a particular lecture
     * @param Carbon $start
     * @param Carbon $end
     * @param int $lectureId
     * @return array
     */
    public function attendanceByLectureId(Carbon $start, Carbon $end, int $lectureId){
        $start = $start->toDateString();
        $end = $end->toDateString();
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
        $start = $start->toDateString();
        $end = $end->toDateString();
        error_log("ERE");
        error_log("ASD".sprintf(AttendanceService::$STUDENT_ATT_BY_ID, $start, $end, $start, $end, $studentId));
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
        $start = $start->toDateString();
        $end = $end->toDateString();
        return DB::select(sprintf(AttendanceService::$ATT_BY_LECTURE_ID_AND_STUDENT_ID, $start, $end, $start, $end,
            $lectureId, $studentId));
    }







}
