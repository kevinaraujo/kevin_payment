<?php

namespace App\Http\Middleware;

use App\Services\Auth\Jwt;
use Closure;

class Authorize
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
        $accessToken = '';

        $jwt = new Jwt();
        $jwt->validate($accessToken);

        return $next($request);
    }
}
