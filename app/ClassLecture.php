<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassLecture extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['teacher_lecture_id', 'created_by', 'updated_by', 'deleted_at', 'description'];
    public const CREATE_VALIDATION_RULES = array(
        'teacher_lecture_id' => 'exists:teacher_lectures,id',
        'created_by'    => 'exists:users,id',
    );
    public const UPDATE_VALIDATION_RULES = array(
        'deleted_at' => 'date_format:Y-m-d H:i:s',
        'updated_by' => 'exist:users,id'
    );
}
