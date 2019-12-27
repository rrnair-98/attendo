<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['user_id', 'token', 'refresh_token', 'expires_at', 'refresh_token_expires_at', 'created_at',
        'updated_at'];

    public static $FLUID_ACCESS_TOKEN = "a8ac3bc4fbeb07d935f9e50eb51e043ee82fbd3f";
    public static $ACESS_TOKEN_DURATION_MILLISECONDS = 432000000; // 5 days
    public static $REFRESH_TOKEN_DURATION_MILLISECONDS = 604800000; // 1 week
    public function user(){
        return $this->belongsTo(User::class);
    }
}
