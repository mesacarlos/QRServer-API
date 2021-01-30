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

use App\Models\User;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Endpoints publicos
$router->post('/v1/login', ['uses' => 'TokensController@login']); //sin hacer aun, falta tabla de tokens
$router->post('/v1/user', ['uses' => 'UsersController@createUser']);

//Endpoints de administrador
$router->group(['middleware' => ['auth', 'is_admin']], function () use ($router) {
	$router->get('/v1/users', ['uses' => 'UsersController@getAllPaginated']);
	$router->get('/v1/user', ['uses' => 'UsersController@getUser']);

});


//Endpoints de usuario
$router->group(['middleware' => ['auth']], function () use ($router) {

});