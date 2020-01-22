<?php


namespace App\GraphQL\Resolvers\Mutations;


use App\Services\AttendanceService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AttendanceMutationResolver
{

    private $attendanceService;

        public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function bulkInsert($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $this->attendanceService->bulkInsert(0, $args['input']);
        return ['message'=>"successfully inserted"];
    }
}
