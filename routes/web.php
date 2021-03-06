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

//Endpoints que no son parte de la API
$router->get('/q/{id}', ['uses' => 'PublicController@qrRedirect']);

//Endpoints publicos
$router->post('/api/v1/login', ['uses' => 'TokensController@login']);
$router->post('/api/v1/register', ['uses' => 'UsersController@registerUser']);
$router->post('/api/v1/emailverify/{id}', ['uses' => 'EmailVerifyTokensController@verify']);
$router->post('/api/v1/forgotpassword/sendtoken', ['uses' => 'ForgotPasswordTokensController@sendToken']);
$router->post('/api/v1/forgotpassword/verify', ['uses' => 'ForgotPasswordTokensController@updatePassword']);

//Endpoints de administrador
$router->group(['middleware' => ['auth', 'is_admin']], function () use ($router) {
	$router->get('/api/v1/users', ['uses' => 'UsersController@getAllPaginated']);
	$router->get('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@getUser']);
	$router->post('/api/v1/user', ['uses' => 'UsersController@createUser']);
	$router->put('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@updateUser']);
	$router->delete('/api/v1/user/{id:[0-9]+}', ['uses' => 'UsersController@deleteUser']);
	$router->get('/api/v1/user/{id:[0-9]+}/qrcodes', ['uses' => 'QRCodesController@getUserQRCodes']);
});

//Endpoints de usuario
$router->group(['middleware' => ['auth']], function () use ($router) {
	//Endpoints de usuario
	$router->delete('/api/v1/logout', ['uses' => 'TokensController@logout']);
	$router->get('/api/v1/user/me', ['uses' => 'UsersController@getLoggedUser']);
	$router->put('/api/v1/user/me', ['uses' => 'UsersController@updateLoggedUser']);
	$router->delete('/api/v1/user/me', ['uses' => 'UsersController@deleteLoggedUser']);
	$router->get('/api/v1/user/me/qrcodes', ['uses' => 'QRCodesController@getLoggedUserQRCodes']);

	//Endpoints para qrcodes
	$router->get('/api/v1/qrcode/{id}', ['uses' => 'QRCodesController@getQRCode']);
	$router->post('/api/v1/qrcode', ['uses' => 'QRCodesController@createQRCode']);
	$router->put('/api/v1/qrcode/{id}', ['uses' => 'QRCodesController@updateQRCode']);
	$router->delete('/api/v1/qrcode/{id}', ['uses' => 'QRCodesController@deleteQRCode']);
	//GET /api/v1/qrcode/{id:[A-Za-z0-9]+}/qrclicks

	//Endpoints para qrclicks

});

$router->get('/test', ['uses' => 'QRClicksController@test']);