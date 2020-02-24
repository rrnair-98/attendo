<?php


namespace App\Http\Middleware;


use App\Services\TokenService;
use Closure;
use Illuminate\Http\Request;

class TokenHeaderMiddleware
{
    private $tokenService;
    private static $ERR_MESSAGE = "Bad request";
    private static $ERR_REASON = "No Authorization Header";
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Continues the chain if a valid token is provided.
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws CustomException If an invalid Token is given.
     */
    public function handle(Request $request, Closure $next){
        $token = $request->header('Authorization');
        if(is_null($token))
            abort(499, "Authorization header token required");
        $accessToken = $this->tokenService->getTokenByAccessToken($token);
        if(is_null($accessToken)){
            abort(498, "Authorization header token required");
        }
        $user = $accessToken->user;
        $request->setUserResolver(function() use ($user){
            return $user;
        });
        $request->merge(['user'=> $user]);
        return $next($request);

    }


}
