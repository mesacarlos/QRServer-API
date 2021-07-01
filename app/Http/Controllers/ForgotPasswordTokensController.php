<?php
namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\Services\ForgotPasswordTokensService;
use App\Models\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordTokensController extends Controller{

	function sendToken(Request $req): JsonResponse{
		$this->validate($req, [
			'email' => 'required|email',
		]);

		$user = UsersService::getUserByEmail($req->get("email"));
		if(!$user)
			return response()->json(true, 200);

		//Creamos un token para ese usuario
		$token = ForgotPasswordTokensService::createToken($user);

		//Enviamos mail con el token
		if(env('APP_DEBUG', true)){
			Mail::to("carlos@mesacarlos.es")->send(new ForgotPassword($user, $token));
		}else{
			Mail::to($req->get('email'))->send(new ForgotPassword($user, $token));
		}

		return response()->json(true, 200);
	}

	function getTokenExists(string $id): JsonResponse{
		$token = ForgotPasswordTokensService::getToken($id);
		if($token)
			return response()->json(true, 200);
		return response()->json(false, 200);
	}

    function updatePassword(Request $req): JsonResponse{
        $token = ForgotPasswordTokensService::getToken($req->get("id"));

        if(!$token)
        	return response() -> json(false, 403);

        $user = UsersService::getUserById($token->user_id);
        if(!$user)
			return response() -> json(false, 403);

		//Si coincide, actualizamos la contraseña del usuario
        UsersService::updateUser($user->id, password: $req->get("password"));
		//Invalidamos el resto de tokens, no los necesita para nada..
		ForgotPasswordTokensService::deleteAllTokensByUser($user->id);

        return response()->json(true, 200);
    }

}
