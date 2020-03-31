<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Query\AttendanceTokenQuery;
use App\Transactors\AttendanceTokenTransactor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AttendanceTokenController extends Controller
{
    //
    private $attendanceTokenTransactor;
    private $attendanceTokenQuery;
    public function __construct(AttendanceTokenTransactor $attendanceTokenService, AttendanceTokenQuery $attendanceTokenQuery)
    {
        $this->attendanceTokenQuery = $attendanceTokenQuery;
        $this->attendanceTokenTransactor = $attendanceTokenService;
    }

    public function createAttendanceToken(Request $request){
        try {
            return response($this->attendanceTokenTransactor->create($request->user));
        }catch (\Exception $exception){
            return ResponseHelper::internalError($exception->getMessage());
        }
    }

    public function markStudentsPresent($teacherLectureId, Request $request){
        $requestBody = $request->all();
        if(array_key_exists("tokens", $requestBody) && is_array($requestBody['tokens'])) {
            try {
                $tokens = $requestBody['tokens'];
                return response($this->attendanceTokenTransactor->markStudentsPresent($request->user->id, $teacherLectureId,
                $tokens));
            } catch (\Exception $e) {
                return ResponseHelper::internalError($e->getMessage());
            }
        }
        return ResponseHelper::badRequest("Expected tokens to be present");
    }

    public function getUserDetailsFromToken($attendanceToken){
        try {
            return response($this->attendanceTokenQuery->getUserFromToken($attendanceToken));
        } catch (ModelNotFoundException $mfe){
            return ResponseHelper::notFound("Couldnt find attendance token");
        }
    }

}
