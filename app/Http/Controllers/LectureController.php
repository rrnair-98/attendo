<?php

namespace App\Http\Controllers;

use App\Services\LectureService;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    //
    private $lectureService;
    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    public function getByTeacher(int $teacherId){
        return response($this->lectureService->findByTeacherId($teacherId));
    }

    public function getByDepartment(int $departmentId){
        return response($this->lectureService->findAllByDepartment($departmentId));
    }

}
