<?php
namespace App\Transactors\Mutations;

class AttendanceTokenMutator extends BaseMutator{
    public function __construct()
    {
        parent::__construct('App\AttendanceToken', 'id');
    }
}