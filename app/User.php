<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;

    const ROLE_STUDENT = 0;
    const ROLE_TEACHER = 8;
    const ROLE_HOD = 65536;
    const ROLE_ADMIN = 2147483647;


    public const CREATE_VALIDATION_RULES = array(
        'role' => 'in:0,8,65536,2147483647',
        'department_id' => 'required',
        'name'  => 'min:3|max:255',
        'phone' => 'unique:users',
        'password'=>'required',
        'email' =>'unique:users',
        'created_by'=> 'exists:users,id'
    );

    public const UPDATE_VALIDATION_RULES = array(
        'role' => 'in:0,8,65536,2147483647',
        'department_id' => 'required',
        'name'  => 'min:3|max:255',
        'phone' => 'unique:users',
        'password'=>'required',
        'email' =>'unique:users',
        'updated_by' => 'exists:users,id',
        'deleted_at'    => 'date_format|Y-m-d H:i:s'
    );

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id', 'created_by', 'updated_by', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'deleted_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];
}
