<?php


namespace App\Query;


use App\User;

class UserQuery
{
    /**
     * Fetches a user instance given the id and the role
     * @param $id ID of the user
     * @param int $userRole Role of the user
     * @return User|null
     */
    public function getUserById($id, $userRole = 1){
        return User::where('id', '=', $id)->where('user_role', '=', $userRole)->first();
    }
    /**
     * Searches a user with the given email, password combo
     * @param $email string Email of the user
     * @param $password string Password of the user
     * @return User|null
     */
    public function getUserByEmailAndPassword($email, $password) {
        return User::where('email', $email)->where('password', hash("sha256",$password))->firstOrFail();
    }

    /**
     * Fetches a list of students, a max of 15 at any request.
     * @param int $limit Max records to fetch
     * @param int $offset The page number
     * @return array[User]
     */
    public function getStudents(int $limit, int $offset=0){
        return $this->fetchUsers($limit, $offset, User::ROLE_STUDENT);
    }

    /**
     * Fetches a list of users, a max of 15 at any given instance.
     * @param int $limit Max records to fetch.
     * @param int $offset The page number. Defaults to 0 when empty.
     * @param int $user_role The role of the user to be fetched.
     * @return array[User]
     */
    private function fetchUsers(int $limit, int $offset, int $user_role){
        $limit = $limit & 0xf;
        return User::where('role', '=', $user_role)->skip($offset)->take($limit)->get();
    }


}
