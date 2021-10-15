<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResponseJsonAndCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Access-Control-Allow-Origin' , '*');
        $request->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $request->headers->set('Access-Control-Allow-Headers', 'X-Requested-With,Accept, Content-Type, X-Token-Auth, Authorization');
        return $next($request);
    }
}
