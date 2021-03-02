<?php
namespace App\Models\Services;

use App\Models\Entities\EmailVerifyToken;
use App\Models\Entities\User;

class EmailVerifyTokensService{

	/**
	 * Generates a new EmailVerifyToken for an user
	 * @param User $user User to generate the EmailVerifyToken
	 * @return EmailVerifyToken The generated EmailVerifyToken
	 */
    static function createEmailVerifyToken(User $user): EmailVerifyToken{
        $tokenStr = bin2hex(random_bytes(20));
		return EmailVerifyToken::create([
			'id' => $tokenStr,
			'user_id' => $user->id,
			'email' => $user->email
		]);
    }

	/**
	 * Retrieve the EmailVerifyToken object for a given EmailVerifyToken Id
	 * @param string $token_id String identifying this EmailVerifyToken object
	 * @return EmailVerifyToken|null EmailVerifyToken object if a matching one is found. NULL otherwise.
	 */
    static function getToken(string $token_id): ?EmailVerifyToken{
    	return EmailVerifyToken::where('id', $token_id) -> first();
	}

	/**
	 * Delete a EmailVerifyToken from database
	 * @param string $token_id id of the EmailVerifyToken
	 * @return bool If a matching EmailVerifyToken was found and deleted
	 */
	static function deleteToken(string $token_id): bool{
    	$count = EmailVerifyToken::destroy($token_id);
    	if($count > 0)
    		return true;
    	return false;
	}

	/**
	 * Delete all EmailVerifyTokens with user_id equals to...
	 * @param int $user_id User Id
	 * @return bool If any EmailVerifyToken was deleted
	 */
	static function deleteAllTokensByUser(int $user_id): bool{
		$count = EmailVerifyToken::where('user_id', $user_id) -> delete();
		if($count > 0)
			return true;
		return false;
	}
}
