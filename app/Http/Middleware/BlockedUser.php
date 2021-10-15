<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlockedUser
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
        $user=User::findOrFail(auth()->guard('user')->user()->id);
        if($user->is_blocked==true){
                return new Response(view('users.user-blocked'));
        }
        return $next($request);
    }
}
