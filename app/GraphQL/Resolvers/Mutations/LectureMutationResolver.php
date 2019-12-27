<?php


namespace App\GraphQL\Resolvers\Mutations;

use App\Services\LectureService as LectureService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LectureMutationResolver
{
    private $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    public function bulkCreate($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        # TODO FIX ME
        $this->lectureService->bulkInsert(/*$context->request()->user->id*/ 1, $args['input']);
        return ['message'=>'insertion complete'];
    }



}
