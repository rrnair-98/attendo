<?php


namespace App\Http\Middleware;
use App\GraphQL\Exceptions\CustomException;
use App\User;
use Illuminate\Http\Request;
use Closure;

class HODMiddleware extends AuthorizationMiddlewareBase
{
    public function handle(Request $request, Closure $next){
        $user = $request->user;
        if($user->role >= User::ROLE_HOD)
            return $next($request);
        throw new CustomException(self::$ERR_MESSAGE, self::$REASON);
    }
}
