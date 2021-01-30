<?php
namespace App\Service;

use App\Models\Token;
use App\Models\User;

class TokensService{

    /**
     * Generates a new token for a user
     * @param User $user User to generate the token
     * @param string $ip IP of the client
     * @return string The generated token
     */
    static function createToken(User $user, string $ip): string{
        $tokenStr = bin2hex(random_bytes(20));
        $token = Token::create([
            'userId' => $user->id,
            'token' => $tokenStr,
            'loginIp' => $ip
        ]);
        return $token->token;
    }
}
