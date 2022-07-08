<?php

namespace App\Http\Middleware;

use App\Helpers\CommonResponseFunction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

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
        $arrayBack = [];
        $arrayErrorInfo = trans('api_msg.success');
        $strDescription = '';
        $strToken = $request->input('access_token');
        $strTokenKey = Config::get('sysconstants.CACHE_INFO.API.PREFIX').'_'.$strToken;
        $arrayCacheValue = Cache::get($strTokenKey);
        if(true === empty($arrayCacheValue)){
           $arrayErrorInfo = trans('api_msg.access_token_expired');
           return CommonResponseFunction::getJsonRespone($arrayErrorInfo,$arrayBack,$strDescription);
        }
        Cache::put($strTokenKey, $arrayCacheValue, Config::get('sysconstants.CACHE_INFO.API.EXPIRES'));
        return $next($request);
    }
}
