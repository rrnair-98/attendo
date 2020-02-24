<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Query\UserQuery;
use App\Services\TokenService;
use App\Services\UserService;
use App\Transactors\AuthTransactor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    private $authTransactor;
    public function __construct(AuthTransactor $authTransactor){
        $this->authTransactor = $authTransactor;
    }

    public function login(Request $request){
        $requestBody = $request->json()->all();
        try {
            if (array_key_exists('email', $requestBody) && array_key_exists('password', $requestBody)) {
                $email = $requestBody['email'];
                $password = $requestBody['password'];
                return $this->authTransactor->login($email, $password);
            }
        }catch (\ErrorException|ModelNotFoundException $exception){
            return ResponseHelper::badRequest('Username password combo was wrong');
        }catch (\Exception $exception){
            return ResponseHelper::internalError($exception->getCode());
        }

    }

    public function logout(Request $request){
        try {
            $this->authTransactor->logout($request->user());
            return response('Successfully logged out');
        }catch (\ErrorException|ModelNotFoundException $exception){
            return ResponseHelper::badRequest('User doesnt exist.');
        }catch (\Exception $exception){
            return ResponseHelper::internalError($exception->getCode());
        }
    }

}
