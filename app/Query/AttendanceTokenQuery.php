<?php


namespace App\Query;


use App\AttendanceToken;
use Carbon\Carbon;

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
            ->join("(select user_id from student_lectures where teacher_lecture_id=$teacherLectureId)
            as student_lectures ", 'student_lectures.user_id', '=', 'attendance_tokens.created_by')
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
}
