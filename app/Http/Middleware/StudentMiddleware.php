<?php


namespace App\Http\Middleware;

use App\GraphQL\Exceptions\CustomException;
use App\User;
use Illuminate\Http\Request;
use Closure;
class StudentMiddleware extends AuthorizationMiddlewareBase
{

    public function handle(Request $request, Closure $next){
        $user = $request->user;
        if($user->role == User::ROLE_STUDENT)
            return $next($request);
        abort(403, "Forbidden.You are not permitted to perform this action");
    }
}
