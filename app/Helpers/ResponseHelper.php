<?php

namespace App\Helpers;


class ResponseHelper
{
    public static function notFound($entity_name, $details = null)
    {
        return response()->json([
            "message" => "No such $entity_name found. $details"
        ], 404);
    }

    public static function internalError($details = null)
    {
        return response()->json([
            "message" => "Some internal server error occurred. $details"
        ], 500);
    }


    public static function forbidden($details = null)
    {
        return response()->json([
            "message" => "Cannot access the given route. $details"
        ], 401);
    }

    public static function badRequest($details){
        return response()->json(['message'=> 'bad request', 'details'=>$details], 400);
    }
}
