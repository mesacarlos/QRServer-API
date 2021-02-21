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
    static function createToken(User $user, string $ip): string{
        $tokenStr = bin2hex(random_bytes(20));
        $token = Token::create([
            'user_id' => $user->id,
            'token_str' => $tokenStr,
            'login_ip' => $ip
        ]);
        return $token->token_str;
    }
}
