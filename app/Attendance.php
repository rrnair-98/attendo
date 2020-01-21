<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    //
    protected $fillable = ['teacher_id', 'created_at', 'lecture_id', 'student_token', 'student_id','created_by_id'];

    public function teacher():BelongsTo{
        return $this->belongsTo(User::class);
    }


    public function student(): BelongsTo{
        return $this->belongsTo(User::class);
    }


}
