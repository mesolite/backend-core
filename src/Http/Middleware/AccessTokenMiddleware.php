<?php

namespace Mesolite\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\HeaderBag;

class AccessTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->request->get('token')) {

        	try {
	        	$token = decrypt($request->request->get('token'));
	        	[$path, $accessToken] = explode(":", $token);

	        	if ($path === "/".$request->path()) {
	        		$repository = new \Laravel\Passport\TokenRepository();
			        $token = $repository->find($accessToken);

			        $user = $token ? $token->user : null;

			        if ($user) {
			            \Illuminate\Support\Facades\Auth::login($user);
			        }
	        	}
	        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

	        }
    	}

        return $next($request);
    }
}
