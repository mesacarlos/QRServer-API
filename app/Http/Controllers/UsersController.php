<?php

namespace App\Http\Controllers;

use App\Mail\VerifyAccount;
use App\Models\Entities\User;
use App\Models\Services\EmailVerifyTokensService;
use App\Models\Services\TokensService;
use App\Models\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller {

	function registerUser(Request $req): JsonResponse {
		$this->validate($req, [
			'username' => 'required|alpha_dash|max:24',
			'email' => 'required|email|unique:users',
			'password' => 'required|max:64'
		]);

		$user = UsersService::createUser(
			$req->get('username'),
			$req->get('email'),
			$req->get('password'),
			$req->ip()
		);
		$user->is_admin = false;

		$emailtoken = EmailVerifyTokensService::createEmailVerifyToken($user);
		Mail::to("carlos@mesacarlos.es")->send(new VerifyAccount($user, $emailtoken));
		//Mail::to($req->get('email'))->send(new VerifyAccount($user, $emailtoken));

		return response() -> json($user, 200);
	}

    function createUser(Request $req): JsonResponse {
        $this->validate($req, [
            'username' => 'required|alpha_dash|max:24',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:64'
        ]);

        $user = UsersService::createUser(
            $req->get('username'),
            $req->get('email'),
            $req->get('password'),
            $req->ip(),
			true
        );

        return response() -> json($user, 201);
    }

    function getAll(): JsonResponse {
        return response() -> json(UsersService::getAllUsers(), 200);
    }

    function getAllPaginated(Request $req): JsonResponse {
		$itemsPerPage = $req->get("itemsPerPage");
		if(!$itemsPerPage)
			$itemsPerPage = 20;
        $users = UsersService::getAllUsersPaginated($itemsPerPage);
        return response() -> json($users, 200);
    }

    function getUser(int $id): JsonResponse {
        if($id <= 0) //Parameter is not set, or is not a valid number (negative or 0)
            return response()->json(['Error' => 'Please provide a valid id.'], 409);

        $user = UsersService::getUserById($id);
        if(!$user)
            return response()->json(['Error' => 'No valid User found for the given id.'], 409);
        return response()->json($user, 200);
    }

	function updateUser(int $id, Request $req): JsonResponse {
		if($id <= 0) //Parameter is not set, or is not a valid number (negative or 0)
			return response()->json(['Error' => 'Please provide a valid id.'], 409);

		$this->validate($req, [
			'username' => 'alpha_dash|max:24',
			'email' => 'email|unique:users',
			'password' => 'max:64'
		]);

		$user = UsersService::updateUser(
			$id,
			$req->get('username'),
			$req->get('email'),
			$req->get('password')
		);
		if(!$user)
			return response()->json(['Error' => 'No valid User found for the given id.'], 409);

		return response()->json($user, 200);
	}

	function updateLoggedUser(Request $req): JsonResponse{
		$this->validate($req, [
			'username' => 'alpha_dash|max:24',
			'email' => 'email|unique:users',
			'password' => 'max:64'
		]);

		$user = UsersService::updateUser(
			Auth::user() -> id,
			$req->get('username'),
			$req->get('email'),
			$req->get('password')
		);

		return response()->json($user, 200);
	}

	function deleteUser(int $id, Request $req): JsonResponse {
		if($id <= 0) //Parameter is not set, or is not a valid number (negative or 0)
			return response()->json(['Error' => 'Please provide a valid id.'], 409);

		$wasDeleted = UsersService::deleteUserById($id);
		return response()->json($wasDeleted, 200);
	}

	function deleteLoggedUser(Request $req): JsonResponse{
    	$user = Auth::user();
		if(!Hash::check($req->get('password'), $user->password))
			return response()->json(false, 403);

		//Password Ok, delete user
		$wasDeleted = UsersService::deleteUserById($user->id);
		return response()->json($wasDeleted, 200);
	}

    function getLoggedUser(): JsonResponse {
		return response()->json(Auth::user(), 200);
	}

}
