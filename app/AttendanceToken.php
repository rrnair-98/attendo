<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceToken extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['is_present', 'created_by', 'updated_by', 'deleted_at', 'class_lecture_id',
        'student_lecture_id', 'token', 'expires_at'];

    public const MAX_EXPIRY_IN_MINUTES = 5;
    public const CREATE_VALIDATION_RULES = array(
        'created_by'    => 'exists:users,id',
        'expires_at'=>'date_format:Y-m-d H:i:s',
        'token' => 'unique:attendance_tokens',
    );

    public const UPDATE_VALIDATION_RULES = array(
        'is_present'    => 'in:0,1',
        'class_lecture_id'=> 'exists:class_lectures,id',
        'updated_by'    => 'exists:users,id',
        'deleted_at'    => 'date_format:Y-m-d H:i:s',
    );

}
