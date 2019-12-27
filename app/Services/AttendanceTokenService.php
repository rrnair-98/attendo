<?php


namespace App\Services;


use App\AttendanceToken;
use Carbon\Carbon;
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
        $baseString = $studentId.Carbon::now('ist').$studentEmail.Str::random();
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

}
