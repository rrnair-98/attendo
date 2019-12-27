<?php


namespace App\GraphQL\Resolvers\Queries;

use App\Services\AttendanceTokenService as AttendanceTokenService;
use GraphQL\Type\Definition\ResolveInfo;use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
class AttendanceTokenQueryResolver
{
    private $attendanceTokenService;
    public function __construct(AttendanceTokenService $attendanceTokenService)
    {
        $this->attendanceTokenService = $attendanceTokenService;
    }

    public function getAttendanceToken($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        # TODO CHECK IF CALLER IS STUDENT THRU MIDDLEWARE
        return $this->attendanceTokenService->findAttendanceTokenByToken($args['token']);
    }

}
