<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    private $userService;
    private $tokenService;

    public function __construct(UserService $userService, TokenService $tokenService){
        $this->userService = $userService;
        $this->tokenService = $tokenService;
    }

    public function postLogin(Request $request){
        if(count($request->json()->all())) {
            $requestBody = $request->json()->all();
            error_log($request->json()->get('password'));
            $email = $requestBody['email'];
            $password = $requestBody['password'];
            $user = $this->userService->getUserByEmailAndPassword($email, $password);
            if($user){
                return response($this->tokenService->generateToken($user));
            }
            return response('Illegal username password combo', 400);
        }
        return response('Bad Request', 400);
    }

}
