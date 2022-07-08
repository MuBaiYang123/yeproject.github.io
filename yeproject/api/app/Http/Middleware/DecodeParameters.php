<?php

namespace App\Http\Middleware;

use App\Helpers\CommonLog;
use App\Helpers\CommonResponseFunction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class DecodeParameters
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
        //访问日志
        CommonLog::writeAccessLog($request->url());

        //判断access_system
        $strAccessSystem = $request->input('access_system');
        //理应定义在系统常量文件中的，sysconstants 利用Config::get()取得
        $arrayAccessSystem = Config::get('sysconstants.ACCESS_SYSTEM');
        if(true === empty($strAccessSystem) || false === in_array($strAccessSystem,$arrayAccessSystem)){
            $arrayErrorInfo = trans('api_msg.parameter_missed');
            return  CommonResponseFunction::getJsonRespone($arrayErrorInfo,$arrayBack,$strDescription);
        }

        //判断 parameters
        $strParameters = $request->input('parameters');
        if(false === empty($strParameters) && true === is_string($strParameters)){
            $arrayParameters = json_decode($strParameters,true);
            $arrayMergeParameters = ['parameters'=>$arrayParameters];
        }else{
            $arrayMergeParameters = ['parameters'=>[]];
        }
        CommonLog::writeAccessLog("parameters=".json_encode($arrayMergeParameters['parameters']));
        $request->merge($arrayMergeParameters);
        return $next($request);
    }
}
