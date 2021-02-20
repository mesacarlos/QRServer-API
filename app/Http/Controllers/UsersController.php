<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\TokensService;
use App\Service\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {

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
            $req->ip()
        );
        return response() -> json($user, 201);
    }

    function getAll(): JsonResponse {
        $users = User::all();
        return response() -> json($users, 200);
    }

    function getAllPaginated(): JsonResponse { //https://laravel.com/docs/master/pagination#paginator-instance-methods
        $items = User::paginate(20) -> items();
        $numItems = User::paginate(20) -> total();
        return response() -> json(array("users" => $items, "totalUsers" => $numItems), 200);
    }

    function getUser(Request $req): JsonResponse{
        $userId = intval($req->get('id'));
        if($userId <= 0) //Parameter is not set, or is not a valid number (negative or 0)
            return response()->json(['Error' => 'Please provide a valid id.'], 409);

        $user = UsersService::getUserById($req->get('id'));
        if(!$user)
            return response()->json(['Error' => 'No valid User found for the given id.'], 409);
        return response()->json($user, 200);
    }

    function getLoggedUser(): JsonResponse{
		return response()->json(Auth::user(), 200);
	}

}
