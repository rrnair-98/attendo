<?php
namespace App\Transactors\Mutations;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceTokenMutator extends BaseMutator{
    public function __construct()
    {
        parent::__construct('App\AttendanceToken', 'id');
    }

    public function generateToken(User $user){
        return hash('sha256', $user->email.$user->created_at.Str::random().Carbon::now()->timestamp);
    }


}
