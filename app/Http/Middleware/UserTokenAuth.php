<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserTokenAuth
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
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'code' => -200,
                    'msg' => 'user not found'
                ]);
            }
        } catch (TokenExpiredException $e) {

            return response()->json([
                'code' => -200,
                'msg' => 'token 失效',
                'm'=> $e->getMessage()
            ]);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'code' => -200,
                'msg' => 'token 无效',
                'm'=> $e->getMessage()
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'code' => -200,
                'msg' => 'token 异常',
                'm'=>$e->getMessage()
            ]);
        }
        return $next($request);
    }
}
