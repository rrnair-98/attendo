<?php


namespace App\Query;


use App\AttendanceToken;
use App\ClassLecture;
use App\StudentLecture;
use App\TeacherLecture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AttendanceTokenQuery
{
    public const OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID=<<<TAG

select students.*, att.percentage, att.total_lectures, %s as teacher_lecture_id from
    (select attendance_tokens.created_by, (count(id)/(select count(id) from class_lectures where teacher_lecture_id = %s)) percentage, (select count(id) from class_lectures where teacher_lecture_id = %s) as total_lectures from attendance_tokens
     where attendance_tokens.class_lecture_id in(select class_lectures.id from class_lectures where class_lectures.teacher_lecture_id = %s)
       and attendance_tokens.is_present and attendance_tokens.created_at between ('%s' and '%s') and deleted_at is null
     group by attendance_tokens.created_by) att
        right join
    (select users.id,users.roll_number, users.name from users where users.role = 0) students on
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
        $query = sprintf(self::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID, $teacherLectureId,
            $teacherLectureId,$teacherLectureId, $teacherLectureId, $fromDate, $toDate);
        return DB::select($query);
    }

    public function getStudentAttendanceByStudentIdAndStudentLectureId($studentId, $studentLectureId){
        $fromDate = Carbon::now()->firstOfYear();
        $toDate = Carbon::now();
        $teacherLectureId = StudentLecture::findOrFail($studentLectureId)->teacher_lecture_id;
        $query = self::OPTIMIZED_FETCH_STUDENT_ATT_FOR_TEACHER_LECTURE_ID. " where students.id = $studentId";
        $query = sprintf($query, $teacherLectureId,  $teacherLectureId, $teacherLectureId, $teacherLectureId, $fromDate, $toDate);
        return DB::select($query);
    }

}
