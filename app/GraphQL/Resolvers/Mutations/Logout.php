<?php

namespace App\GraphQL\Resolvers\Mutations;

use App\Services\TokenService;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Logout
{
    private $tokenService;

    public function __construct(){
        $this->tokenService = new TokenService();
    }
    /**
     * @param $rootValue
     * @param array $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return array
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $token = $context->request()->header('Authorization');
        $this->tokenService->revokeToken($token);
        return ['status'=>'success','message' => 'you have been logged out'];
    }

}
