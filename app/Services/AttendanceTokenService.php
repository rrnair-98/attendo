<?php


namespace App\Services;


use App\AttendanceToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceTokenService
{
    /**
     * Finds an attendance token with the given string
     * @param string $token
     * @return AttendanceToken
     */
    public function findAttendanceTokenByToken(string $token){
        return AttendanceToken::where('token', '=', $token)->get();
    }

    /**
     * Creates or returns an existing token of the current user.
     * @param int $studentId
     * @param string $studentEmail
     * @return AttendanceToken
     */
    public function createToken(int $studentId, string $studentEmail){
        $baseString = $studentId.Carbon::now().$studentEmail.Str::random();
        $existingToken = $this->findCurrentlyActiveToken($studentId);
        if($existingToken != null)
            return $existingToken;
        return AttendanceToken::create([
            'student_id'=>$studentId,
            'token' => hash('sha256', $baseString.strrev($baseString)),
            'created_at'=>Carbon::now('ist')
        ]);
    }

    /**
     * Returns the active bound to the given student id.
     * @param int $studentId
     * @return AttendanceToken
     */
    private function findCurrentlyActiveToken(int $studentId){
        error_log(Carbon::now('ist')->subMinute(5));
        return AttendanceToken::where('student_id', '=', $studentId)
            ->where('created_at', '>=', Carbon::now()->subMinutes(AttendanceToken::TOKEN_VALIDITY_IN_MINUTES))
            ->where('created_at', '<=', Carbon::now())
            ->first();
    }

    /**
     * Returns a result set of students joined to attendance_tokens with valid
     * @param array $studentTokens
     * @param int $teacherId To be appended in the resultant rows
     * @param int $lectureId To be appended in the resultant rows
     * @return \Illuminate\Support\Collection
     */
    public function getValidStudentDataFromTokens(array $studentTokens, int $teacherId, int $lectureId){
        foreach ($studentTokens as &$token){
            $token = "'$token'";
        }
        return DB::table('attendance_tokens')
            //todo remove already used token from this join
            ->join('users', 'users.id','=', 'attendance_tokens.student_id')
            ->whereIn('attendance_tokens.token', $studentTokens)
            ->where('attendance_tokens.created_at', '>=', Carbon::now()->subMinutes(AttendanceToken::TOKEN_VALIDITY_IN_MINUTES))
            ->where('attendance_tokens.created_at', '<=', Carbon::now())
            ->select('users.id as student_id', 'attendance_tokens.token as student_token',
                DB::raw("$teacherId as teacher_id") , DB::raw("$lectureId as lecture_id"))
            ->get();
    }

}
