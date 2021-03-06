<?php
namespace App\Models\Services;

use App\Models\Entities\EmailVerifyToken;
use App\Models\Entities\ForgotPasswordToken;
use App\Models\Entities\User;

class ForgotPasswordTokensService{

	/**
	 * Generates a new ForgotPasswordToken for an user
	 * @param User $user User to generate the ForgotPasswordToken
	 * @return ForgotPasswordToken The generated ForgotPasswordToken
	 */
    static function createToken(User $user): ForgotPasswordToken{
        $tokenStr = bin2hex(random_bytes(20));
		while(self::getToken($tokenStr))
			$tokenStr = bin2hex(random_bytes(20));
		return ForgotPasswordToken::create([
			'id' => $tokenStr,
			'user_id' => $user->id
		]);
    }

	/**
	 * Retrieve the ForgotPasswordToken object for a given ForgotPasswordToken Id
	 * @param string $token_id String identifying this ForgotPasswordToken object
	 * @return ForgotPasswordToken|null ForgotPasswordToken object if a matching one is found. NULL otherwise.
	 */
    static function getToken(string $token_id): ?ForgotPasswordToken{
    	return ForgotPasswordToken::where('id', $token_id) -> first();
	}

	/**
	 * Delete a ForgotPasswordToken from database
	 * @param string $token_id id of the ForgotPasswordToken
	 * @return bool If a matching ForgotPasswordToken was found and deleted
	 */
	static function deleteToken(string $token_id): bool{
    	$count = ForgotPasswordToken::destroy($token_id);
    	if($count > 0)
    		return true;
    	return false;
	}

	/**
	 * Delete all ForgotPasswordToken with user_id equals to...
	 * @param int $user_id User Id
	 * @return bool If any ForgotPasswordToken was deleted
	 */
	static function deleteAllTokensByUser(int $user_id): bool{
		$count = ForgotPasswordToken::where('user_id', $user_id) -> delete();
		if($count > 0)
			return true;
		return false;
	}
}
