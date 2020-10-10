<?php

namespace App\Http\Middleware;

use Closure;

class AuthBasic
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
        if($request->header('Authorization')  !== env("AUTHENTICATIONKEY")){
            return response()->json(["message" => "Auth Failed"]);
        }else{
            return $next($request);
        }
        
    }
}
