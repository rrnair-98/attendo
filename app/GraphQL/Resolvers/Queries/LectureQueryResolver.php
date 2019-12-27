<?php


namespace App\GraphQL\Resolvers\Queries;


use App\GraphQL\Exceptions\CustomException;
use App\Services\LectureService as LectureService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LectureQueryResolver
{
    private $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     * @throws CustomException
     */
    public function findAllByDepartmenId($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){

        $lectures = $this->lectureService->findAllByDepartment($args['departmentId']);
        if($lectures)
            return $lectures;
        throw new CustomException("Not Found", sprintf(CustomException::$RESOURCE_ERROR, "Lecture", "dept id ", "not found"));

    }

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     * @throws CustomException
     */
    public function findAllByDepartmendIdAndTeacherId($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $lectures = $this->lectureService->findByTeacherAndDepartment($args['teacherId'], $args['departmendId']);
        if($lectures)
            return $lectures;
        throw new CustomException("Not Found", sprintf(CustomException::$RESOURCE_ERROR, "Lecture", "dept id ", "not found"));

    }

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return \phpDocumentor\Reflection\Types\Array_
     * @throws CustomException
     */
    public function findAllByTeacherId($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $lectures = $this->lectureService->findByTeacherId($args['teacherId']);
        if($lectures)
            return $lectures;
        throw new CustomException("Not Found", sprintf(CustomException::$RESOURCE_ERROR, "Lecture", "dept id ", "not found"));

    }

    /**
     * @param $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     * @throws CustomException
     */
    public function findAllByDepartmentAndDayOfWeek($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $lectures = $this->lectureService->findAllByDepartmentAndDayOfWeek($args['dayOfWeek'], $args['departmentId']);
        if($lectures)
            return $lectures;
        throw new CustomException("Not Found", sprintf(CustomException::$RESOURCE_ERROR, "Lecture", "dept id ", "not found"));
    }



}
