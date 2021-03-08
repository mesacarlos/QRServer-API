<?php

namespace App\Providers;

use App\Models\Entities\Token;
use App\Models\Entities\User;
use App\Models\Services\TokensService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot(){
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
        	$api_token = $request->header('apitoken');
            if ($api_token) {
            	$tokenObj = TokensService::getToken($api_token);
            	if($tokenObj != NULL)
            		return User::where('id', $tokenObj->user_id) -> first();
                //return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
