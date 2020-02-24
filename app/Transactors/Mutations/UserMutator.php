<?php
namespace App\Transactors\Mutations;

class UserMutator extends BaseMutator{
    public function __construct()
    {
        parent::__construct('App\User', 'id');
    }
}