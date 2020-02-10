<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const ROLE_STUDENT = 0;
    const ROLE_TEACHER = 8;
    const ROLE_HOD = 65536;
    const ROLE_ADMIN = 2147483647;


    public function attendanceToken(): HasMany{
        return $this->hasMany(\App\AttendanceToken::class);
    }

    public function taughtLecture(): HasMany{
            return $this->$this->hasMany(\App\Lecture::class);
    }


    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];
}
