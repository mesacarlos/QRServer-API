<?php
namespace App\Http\Controllers;

use App\Models\Services\EmailVerifyTokensService;
use App\Models\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerifyTokensController extends Controller{

    function verify(Request $req, string $id): JsonResponse{
        $emailtoken = EmailVerifyTokensService::getToken($id);

        if(!$emailtoken)
        	return response() -> json(false, 403);

        $user = UsersService::getUserById($emailtoken->user_id);
        if(!$user)
			return response() -> json(false, 403);

        if($user-> email != $emailtoken->email)
			return response() -> json(false, 403);

		//Si coincide, marcamos el email_verified del usuario a true y borramos todos los token de verificacion del usuario
        UsersService::setUserEmailVerified($user->id);
        EmailVerifyTokensService::deleteAllTokensByUser($user->id);

        return response()->json(true, 200);
    }

}
