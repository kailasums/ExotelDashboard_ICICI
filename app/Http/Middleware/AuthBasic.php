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
        header('WWW-Authenticate: Basic realm="My Realm"');
        if (!isset($_SERVER['PHP_AUTH_USER'])) {    
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(['error' => 'auth fail']);
            exit();
        } else {
            if($_SERVER['PHP_AUTH_USER'] === env("sid") && $_SERVER['PHP_AUTH_PW'] === env("sidToken")){
                return $next($request);
            }else{
                echo  json_encode(['error' => 'auth fail']);exit();
            } 
        }
    }
}
