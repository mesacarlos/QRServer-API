<?php namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$headers = null;
		if(env('APP_DEBUG', true)){
			$headers = [
				'Access-Control-Allow-Origin'      => 'http://localhost:4200',
				'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
				'Access-Control-Allow-Credentials' => 'true',
				'Access-Control-Max-Age'           => '86400',
				'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, api_token'
			];
		}else{
			$http_origin = $_SERVER['HTTP_ORIGIN'];
			if ($http_origin == "http://mesacarlos.es" || $http_origin == "https://mesacarlos.es") {
				$headers = [
					'Access-Control-Allow-Origin'      => $http_origin, //TODO Cambiar a qr.mesacarlos.es, para http y https
					'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
					'Access-Control-Allow-Credentials' => 'true',
					'Access-Control-Max-Age'           => '86400',
					'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, api_token'
				];
			} else {
				$headers = [
					'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
					'Access-Control-Allow-Credentials' => 'true',
					'Access-Control-Max-Age'           => '86400',
					'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, api_token'
				];
			}
		}


		if ($request->isMethod('OPTIONS'))
		{
			return response()->json('{"method":"OPTIONS"}', 200, $headers);
		}

		$response = $next($request);
		foreach($headers as $key => $value)
		{
			$response->header($key, $value);
		}

		return $response;
	}
}