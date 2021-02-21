<?php
namespace App\Models\Services;

use App\Models\Entities\User;
use Illuminate\Support\Facades\Hash;

class UsersService{

    /**
     * Creates a new user
     * @param $username string User username
     * @param $email string User email
     * @param $password string User password
     * @param $ip string Source IP of the sign up request
     * @return User user object
     */
    static function createUser(string $username, string $email, string $password, string $ip){
        return User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'registered_ip' => $ip
        ]);
    }

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
