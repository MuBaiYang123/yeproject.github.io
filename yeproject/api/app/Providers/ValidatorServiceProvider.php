<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //校验密码  ---这里不能直接用密码，因为校验规则定义中有password!
        Validator::extend('password_check', function ($attribute, $value, $parameters) {
            //获取自定义的密码校验正则
            $strPasswordCheck = Config::get('sysconstants.PASSWORD_REG');
            //进行正则匹配 preg_match($pattern,$subject)执行匹配正则表达式,搜索subject与pattern给定的正则表达式是否匹配；返回结果，0不匹配，或者1次
            //与之区分的是preg_match_all()函数，一直搜索到subject直到结尾，而上一个只搜索一次。
            if (preg_match($strPasswordCheck, $value)) {
                return true;
            } else {
                return false;
            }
        });

        //校验手机号--- 必须11位
        Validator::extend('tel_check',function ($attribute,$value,$parameters){
            $strTelCheck = Config::get('sysconstants.TEL_REG');
            if(preg_match($strTelCheck,$value)){
                return true;
            }else{
                return  false;
            }
        });

        //校验所有传入的ID 是否是数字，若不是数字则表示有注入！
        Validator::extend('id_check',function ($attribute,$value,$parameters){
            $arrayValue = explode(',', $value);
            foreach ($arrayValue as $val) {
                if (Config::get('sysconstants.NORMAL_NUM.ZERO') === preg_match("/^\d*$/", $val)) {
                    return false;
                }
            }
            return true;
        });

    }
}
