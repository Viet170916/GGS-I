<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthentication extends Middleware {
    public function handle( $request, Closure $next, ...$guards ) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch( TokenExpiredException $e ) {
            return response()->json( [ 'message' => 'Token has expired' ], 401 );
        } catch( TokenInvalidException $e ) {
            return response()->json( [ 'message' => 'Token is invalid' ], 401 );
        } catch( JWTException $e ) {
            return response()->json( [ 'message' => 'Token is missing' ], 401 );
        }
        if( !$user ) {
            return response()->json( [ 'message' => 'User not found' ], 404 );
        }
        return $next( $request );
    }
}
