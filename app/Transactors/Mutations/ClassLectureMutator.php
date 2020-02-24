<?php
namespace App\Transactors\Mutations;

class ClassLectureMutator extends BaseMutator{
    public function __construct()
    {
        parent::__construct('App\ClassLecture', 'id');
    }
}