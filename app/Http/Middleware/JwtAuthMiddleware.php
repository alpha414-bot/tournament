<?php


namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtAuthMiddleware extends BaseMiddleware
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
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return ResponseHelper::response_unauthorized('Token is Invalid', []);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return ResponseHelper::response_unauthorized('Token is Expired', []);
            }else{
                return ResponseHelper::response_unauthorized('Authorization Token not found', []);
            }
        }
        return $next($request);
    }
}
