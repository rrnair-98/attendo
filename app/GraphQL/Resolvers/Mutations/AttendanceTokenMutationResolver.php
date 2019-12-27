<?php


namespace App\GraphQL\Resolvers\Mutations;
use App\Services\AttendanceTokenService as AttendanceTokenService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AttendanceTokenMutationResolver
{

    private $attendanceTokenService;
    public function __construct(AttendanceTokenService $attendanceTokenService)
    {
        $this->attendanceTokenService = $attendanceTokenService;
    }

    public function createAttendanceToken($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        # TODO CHECK IF CALLER IS STUDENT THRU MIDDLEWARE
        # FIXME
        return $this->attendanceTokenService->createToken(/*$context->request()->user->id*/ 1, Str::random());
    }

}
