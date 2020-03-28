<?php


namespace App\Query;


use App\AttendanceToken;
use App\ClassLecture;
use App\TeacherLecture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AttendanceTokenQuery
{
    public const OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID=<<<TAG
select students.*, att.num from
(select attendance_tokens.created_by, (count(id)/%s) num from attendance_tokens
where attendance_tokens.class_lecture_id
in
    (select class_lectures.id from class_lectures where class_lectures.teacher_lecture_id in (%s))
and attendance_tokens.is_present and attendance_tokens.created_at between %s and %s
group by attendance_tokens.created_by) att
right join
(select users.id, users.email, users.img_url from users where users.role = 0) students on
students.id = att.created_by
TAG;

    /**
     * Fetches tokens of students subscribed to the specified class.
     * @param array $tokens An array of tokens that were received
     * @param $teacherLectureId ID The teacher lecture this belongs to
     * @return mixed A list of
     */
    public function getValidAttendanceTokensFromList(array $tokens, $teacherLectureId){
        return AttendanceToken::whereIn('token', $tokens)
            ->join(DB::raw("(select user_id from student_lectures where teacher_lecture_id=$teacherLectureId) as student_lectures "),
            function ($join){
                $join->on('student_lectures.user_id', '=', 'attendance_tokens.created_by');
            })
            ->select('attendance_tokens.token as token')
            ->get();
    }

    /**
     * Fetches an AttendanceToken instance for the given student id that hasnt expired.
     * @param $studentId ID The id of the student
     * @return mixed The attendance token, returns null otherwise
     */
    public function getNonExpiredAttendanceTokenForStudent($studentId){
        return AttendanceToken::where('created_by', $studentId)
            ->where('expires_at', '>=', Carbon::now())
            ->first();
    }

    /**
     * Fetches the name, email, img_url and id of the user bound to this token
     * @param string $attendanceToken
     * @return mixed
     * @throws ModelNotFoundException if no such token exists.
     */
    public function getUserFromToken(string $attendanceToken){
        return AttendanceToken::join('users', 'users.id', '=', 'attendance_tokens.created_by')
        ->where('token', $attendanceToken)
        ->select('users.id','users.name', 'users.email', 'users.img_url')
        ->firstOrFail();
    }


    /**
     * Fetches student's name, email, img_url that sat for this class lecture.
     * @param $classLectureId
     * @return mixed
     */
    public function getStudentsInClassLecture($classLectureId){
        return AttendanceToken::where('class_lecture_id', $classLectureId)
            ->join('users', 'users.id', '=', 'attendance_tokens.created_by')
            ->select('users.id','users.name', 'users.email', 'users.img_url')
            ->get();
    }

    public function getStudentAttendanceByTeacherLectureId($teacherLectureId, $fromDate=null, $toDate=null){
        if($fromDate == null)
            $fromDate = Carbon::now()->firstOfYear();
        if($toDate == null)
            $toDate = Carbon::now();
        $fromDate = Carbon::now()->lastOfMonth();

        $numLectures = ClassLecture::where('teacher_lecture_id', $teacherLectureId)->select(DB::raw('count(id) as num'))->get();
        if(count($numLectures)) {
            $numLectures = $numLectures[0]['num'];
            $query = sprintf(self::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID, $numLectures,
            $teacherLectureId, $fromDate, $toDate);
            return DB::select($query);
        }
        throw new ModelNotFoundException();
    }

}
