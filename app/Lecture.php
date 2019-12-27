<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecture extends Model
{
    //
    protected $fillable = ['teacher_id', 'subject_name', 'created_at', 'created_by', 'updated_at', 'day_of_week',
        'department_id', 'lecture_number'];

    public function teacher(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
