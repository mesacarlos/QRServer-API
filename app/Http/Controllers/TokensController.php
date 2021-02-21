<?php
namespace App\Http\Controllers;

use App\Models\Services\TokensService;
use App\Models\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokensController extends Controller{

    function login(Request $req): JsonResponse{
        $this->validate($req, [
            'email' => 'required|email',
            'password' => 'required|max:64'
        ]);

        $user = UsersService::getUserByEmail($req->get('email'));
        if($user === NULL)
            return response()->json(['Error' => 'Incorrect email or password. Please try again.'], 403); //Bad email
        if(!Hash::check($req->get('password'), $user->password))
            return response()->json(['Error' => 'Incorrect email or password. Please try again.'], 403); //Bad password

        $token = TokensService::createToken($user, $req->ip());

        return response()->json(['api_token' => $token], 200);
    }

}
