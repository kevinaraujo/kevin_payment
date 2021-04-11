<?php

namespace App\Http\Middleware;

use App\Services\Auth\Jwt;
use Closure;
use Illuminate\Http\Response;
use \Emarref\Jwt\Exception\VerificationException;

class Authenticate
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
        try {
            $accessToken = $request->bearerToken();

            $jwt = new Jwt();
            $payload = $jwt->validate($accessToken);

            $request->merge([
                'email' => $payload['email']
            ]);

            return $next($request);

        } catch (VerificationException | \Exception $ex) {

            return response()->json([
                'message' => 'INVALID_CREDENTIALS'
            ], Response::HTTP_UNAUTHORIZED);

        }
    }

}
