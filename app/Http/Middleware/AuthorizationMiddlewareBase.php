<?php


namespace App\Http\Middleware;


class AuthorizationMiddlewareBase
{
    protected static $ERR_MESSAGE = "Authorization Error";
    protected static $REASON = "You arent authorized to view this resource";
}
