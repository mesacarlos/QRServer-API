<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersService{
    /**
     * Gets the User object corresponding to a given email
     * @param $email email del usuario
     * @return User first user with given email
     */
    static function getUserByEmail($email){
        return User::where('email', $email) -> first();
    }

    /**
     * Gets the User object corresponding to a given Id
     * @param $id Id of the user
     * @return User user with the given Id
     */
    static function getUserById($id){
        return User::where('id', $id) -> first();
    }

}
