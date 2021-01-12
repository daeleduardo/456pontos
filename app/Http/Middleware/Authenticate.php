<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Manipula as requisições, validando se as requisições a serviços restritos são válidos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!$request->headers->has('Authorization')){
            return response('Unauthorized.', 401);
        }
        
        if($request->header("Authorization") != getenv("API_TOKEN")){
            return response('Forbidden.', 403);
        }

        return $next($request);

        
    }
}
