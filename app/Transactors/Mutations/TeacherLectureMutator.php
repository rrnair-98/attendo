<?php
namespace App\Transactors\Mutations;

class TeacherLectureMutator extends BaseMutator{
    public function __construct()
    {
        parent::__construct('App\TeacherLecture', 'id');
    }
}