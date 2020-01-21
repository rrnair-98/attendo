<?php

namespace App\Http\Controllers;

use App\Services\AttendanceTokenService;
use Illuminate\Http\Request;

class AttendanceTokenController extends Controller
{
    //
    private $attendanceTokenService;
    public function __construct(AttendanceTokenService $attendanceTokenService)
    {
        $this->attendanceTokenService = $attendanceTokenService;
    }

    public function postCreateAttendanceToken(Request $request){
        return response($this->attendanceTokenService->createToken($request->user->id, $request->user->email));
    }

}
