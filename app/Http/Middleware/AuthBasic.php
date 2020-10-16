<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        //if(($request->header('apikey')  !== env("apikey")) && $request->header('sid')  !== env("sid")){
           // if(Auth::onceBasic())    {
        $AUTH_USER = env('sid');
        $AUTH_PASS = env('sidToken');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            // header('HTTP/1.1 401 Authorization Required');
            // header('WWW-Authenticate: Basic realm="Access denied"');
            return response()->json(["message" => "Auth Failed"], 401);
            exit;
        }
        return $next($request);
    //}

            
        //     return response()->json(["message" => "Auth Failed"], 401);
        // }else{
            
        // }
        return $next($request);
    }
}
