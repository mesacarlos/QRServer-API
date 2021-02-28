<?php
namespace App\Http\Controllers;

use App\Mail\VerifyAccount;
use App\Models\Services\TokensService;
use App\Models\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
		if($user->verified_email == false) {
			Mail::to("carlos@mesacarlos.es")->send(new VerifyAccount($user));
			//Mail::to($req->get('email'))->send(new VerifyAccount($user));
			return response()->json(['Error' => 'Please verify your email in order to enable login'], 403); //Account not verified
		}
        $token = TokensService::createToken($user, $req->ip());

        return response()->json(['api_token' => $token->id], 200);
    }

    function logout(Request $req): JsonResponse{
    	$deleted = TokensService::deleteToken($req->header('api_token'));
		return response()->json(['loggedOut' => $deleted], 200);
	}

}
