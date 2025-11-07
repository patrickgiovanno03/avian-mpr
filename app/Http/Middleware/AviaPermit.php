<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AviaPermit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $menu_id = 0, $permit = 'view')
    {
        if (!$request->user()->permitted($menu_id, $permit)) {
            return \abort(401, 'Anda tidak memiliki permission untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
