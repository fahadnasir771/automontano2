<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Acceptor
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role == 2) {
            return $next($request);
        }

        $destinations = [
            1 => 'admin',
            2 => 'acceptor',
            3 => 'operator',
            4 => 'customer'
        ];

        return redirect()->route($destinations[Auth::user()->role] . '.dashboard'); 
    }
}
