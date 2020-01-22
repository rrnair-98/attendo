<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AttendanceToken extends Model
{
    //
    const TOKEN_VALIDITY_IN_MINUTES = 5;
    protected $fillable = ['student_id', 'token'];

    public function student(): BelongsTo{
        return $this->belongsTo(User::class);
    }


}
