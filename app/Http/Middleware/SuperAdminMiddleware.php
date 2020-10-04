<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
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
        if (Auth::user()->is_admin !== "YES") {
            $this->redirect('/home');
            //abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
