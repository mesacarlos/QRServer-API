<?php
namespace App\Models\Services;

use App\Models\Entities\User;
use Illuminate\Database\Eloquent\Collection;
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
    static function createUser(string $username, string $email, string $password, string $ip, bool $verified_email = false): User{
        return User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'registered_ip' => $ip,
			'verified_email' => $verified_email
        ]);
    }

	/**
	 * Return all users in the database
	 * @return Collection|array Array containing all users
	 */
    static function getAllUsers(): Collection|array{
    	return User::all();
	}

	/**
	 * Get all users paginated
	 * @param int $usersPerPage Number of users per page
	 * @return array Array containing all users and
	 */
	static function getAllUsersPaginated(int $usersPerPage){ //https://laravel.com/docs/master/pagination#paginator-instance-methods
		$items = User::paginate($usersPerPage) -> items();
		$numItems = User::paginate($usersPerPage) -> total();
    	return array("users" => $items, "totalUsers" => $numItems);
	}

	/**
	 * Gets the User object corresponding to a given email
	 * @param $email string email del usuario
	 * @return User|null first user with given email, null if no user was found
	 */
    static function getUserByEmail(string $email): ?User{
        return User::where('email', $email) -> first();
    }

	/**
	 * Gets the User object corresponding to a given Id
	 * @param $id int of the user
	 * @return User|null user with the given Id, null if no user was found
	 */
    static function getUserById(int $id): ?User{
        return User::where('id', $id) -> first();
    }

	/**
	 * Updates an user
	 * @param int $id id of the user
	 * @param string|null $username new username or null if unchanged
	 * @param string|null $email new email or null if unchanged
	 * @param string|null $password new password or null if unchanged
	 * @return User|null updated user object. Null if user with given ID not found
	 */
    static function updateUser(int $id, ?string $username, ?string $email, ?string $password): ?User {
		$user = User::find($id);
		if(!$user)
			return NULL;
		if($username)
			$user->username = $username;
		if($email){
			$user->email = $email;
			$user->verified_email = false;
		}
		if($password)
			$user->password = Hash::make($password);
		$user->save();
		return $user;
	}

	/**
	 * Set verified_email to true
	 * @param $user_id User id
	 * @return User|null Updated user if the user exists in database. NULL otherwise
	 */
	static function setUserEmailVerified($user_id): ?User{
		$user = User::find($user_id);
		if(!$user)
			return NULL;

		$user->verified_email = true;
		$user->save();
		return $user;
	}

	/**
	 * Delete the User gith the given ID
	 * @param int $id if of the user
	 * @return bool true if any user was deleted. False otherwise
	 */
    static function deleteUserById(int $id): bool{
    	$count = User::destroy($id);
    	return $count > 0;
	}

}
