<?php

namespace App\GraphQL\Resolvers\Mutations;

use App\AccessToken;
use App\GraphQL\Exceptions\CustomException;
use App\Services\StudentService;
use App\Services\TokenService;
use App\Services\UserService;
use App\Student;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Login
{
    private $userService;
    private $tokenService;
    private $studentService;
    private static $REASON = "Bad username password combo";
    private static $OTP_FAILED_REASON = "Bad phonenumber otp combo. Prolly it has expired.";
    public function __construct(){
        $this->userService = new UserService();
        $this->tokenService = new TokenService();
        $this->studentService = new StudentService();
    }

    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo): AccessToken
    {
        $user = $this->userService->getUserByEmailAndPassword($args['data']['username'], $args['data']['password']);
        error_log($user);
        if (is_null($user)) {
            $error = sprintf(CustomException::$RESOURCE_ERROR, "User", "username passoword", "not found");
            throw new CustomException($error, Login::$REASON);
        }
        return $this->tokenService->generateToken($user);
    }

    public function loginWithOtp($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): AccessToken{
        $student = $this->studentService->getStudentByPhoneAndOtp($args['phoneNumber'], $args['otp']);
        error_log(\GuzzleHttp\json_encode($student));
        if(is_null($student)) {
            $error = sprintf(CustomException::$RESOURCE_ERROR, "Student", "phone otp", "not found");
            throw new CustomException(Login::$OTP_FAILED_REASON, $error);
        }
        $this->studentService->invalidateOtp($student);
        return $this->tokenService->generateToken($student->user);
    }

}
