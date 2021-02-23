<?php
namespace App\Models\Services;

use App\Models\Entities\Token;
use App\Models\Entities\User;

class TokensService{

    /**
     * Generates a new token for a user
     * @param User $user User to generate the token
     * @param string $ip IP of the client
     * @return string The generated token
     */
    static function createToken(User $user, string $ip): Token{
        $tokenStr = bin2hex(random_bytes(20));
        $token = Token::create([
			'id' => $tokenStr,
            'user_id' => $user->id,
            'login_ip' => $ip
        ]);
        return $token;
    }

	/**
	 * Retrieve the Token object for a given token id
	 * @param string $token_id String identifying this token object
	 * @return Token|null Token object if a matching one is found. NULL otherwise.
	 */
    static function getToken(string $token_id): ?Token{
    	return Token::where('id', $token_id) -> first();
	}

	/**
	 * Delete a Token from database
	 * @param string $token_id id of the token
	 * @return bool If a matching token was found and deleted
	 */
	static function deleteToken(string $token_id): bool{
    	$count = Token::destroy($token_id);
    	if($count > 0)
    		return true;
    	return false;
	}
}
