<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $strToken = Request::input('access_token');
        $arrayCacheValue = Cache::get($strToken);
        if(true === empty($arrayCacheValue)){
            return false;
        }
        Cache::put($strToken,$arrayCacheValue,'600');
        return $next($request);
    }
}
