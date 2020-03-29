<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLecture extends Model
{
    public $timestamps = true;
    use SoftDeletes;

    public function teacherLectures(){
        return $this->belongsTo('App\TeacherLecture', 'teacher_lecture_id');
    }

    //
}
