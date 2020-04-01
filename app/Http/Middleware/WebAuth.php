<?php

namespace App\Http\Middleware;

use App\Models\baseinfo\Student;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;

class WebAuth
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
        $token = JWTAuth::setRequest($request)->getToken();
        try {
            $uId = JWTAuth::setToken($token)->getPayload()->get('sub');
            auth()->setUser(Student::findOrFail($uId));
        } catch (JWTException $e) {
        }
        return $next($request);
    }
}
