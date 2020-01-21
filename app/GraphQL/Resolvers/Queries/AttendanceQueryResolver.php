<?php


namespace App\GraphQL\Resolvers\Queries;


use App\Services\AttendanceService;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AttendanceQueryResolver
{

    private $attendanceService;
    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getAttendanceByLectureId($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        return $this->attendanceService->attendanceByLectureId(Carbon::parse($args["start"]),
            Carbon::parse($args["end"]), $args["lectureId"]);
    }

    public function getAttendanceByStudentId($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        error_log(json_encode($this->attendanceService->attendanceByStudentId(Carbon::parse($args["start"]),
            Carbon::parse($args["end"]), $args["studentId"])));
        return $this->attendanceService->attendanceByStudentId(Carbon::parse($args["start"]),
            Carbon::parse($args["end"]), $args["studentId"]);
    }

    public function getAttendanceByStudentAndLecture($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->attendanceService->attendanceByStudentAndLecture(Carbon::parse($args["start"]),
            Carbon::parse($args["end"]), $args["studentId"], $args["lectureId"]);
    }



}
