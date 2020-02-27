<?php


namespace App\Query;


use App\AttendanceToken;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AttendanceTokenQuery
{
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
}
