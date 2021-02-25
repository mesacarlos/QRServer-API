<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Endpoints publicos
$router->post('/api/v1/login', ['uses' => 'TokensController@login']); //sin hacer aun, falta tabla de tokens
//register
$router->post('/api/v1/user', ['uses' => 'UsersController@createUser']); //TODO Este luego será de admin, y aqui se cambiara por register

//Endpoints de administrador
$router->group(['middleware' => ['auth', 'is_admin']], function () use ($router) {
	$router->get('/api/v1/users', ['uses' => 'UsersController@getAllPaginated']);
	$router->get('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@getUser']);
	//POST /api/v1/user
	$router->put('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@updateUser']);
	$router->delete('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@deleteUser']);
	//GET /api/v1/user/{id:[0-9]+}/qrcodes
});

//Endpoints de usuario
$router->group(['middleware' => ['auth']], function () use ($router) {
	//Endpoints de usuario
	$router->delete('/api/v1/logout', ['uses' => 'TokensController@logout']);
	$router->get('/api/v1/user/me', ['uses' => 'UsersController@getLoggedUser']);
	$router->put('/api/v1/user/me', ['uses' => 'UsersController@updateLoggedUser']);
	$router->delete('/api/v1/user/me', ['uses' => 'UsersController@deleteLoggedUser']);
	//GET /api/v1/user/me/qrcodes

	//Endpoints para qrcodes

	//Endpoints para qrclicks

});

$router->get('/test', ['uses' => 'QRClicksController@test']);