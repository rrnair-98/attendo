<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherLecture extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['user_id', 'created_by', 'updated_by', 'deleted_at', 'lecture_id'];
    public const CREATE_VALIDATION_RULES = array(
        'user_id'   => 'exists:users,id',
        'lecture_id'    => 'exists:lectures,id',
        'created_by'    => 'exists:users,id'
    );

    public const UPDATE_VALIDATION_RULES = array(
      'deleted_at'  => 'date_format|Y-m-d H:i:s',
      'updated_by'  => 'exists:users,id',
    );
}
