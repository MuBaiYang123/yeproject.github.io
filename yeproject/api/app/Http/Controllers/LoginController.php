<?php

namespace App\Http\Controllers;

use App\Helpers\CommonLog;
use App\Helpers\CommonResponseFunction;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * @description  用户登录
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string[]
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public function login()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                'login_id',
                'password'
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误

                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $arrayCheckedParams = [
                'login_id' => $arrayParams['login_id'],
                'password' => $arrayParams['password']
            ];
            $arrayUserInfo = User::getUserInfoByUserName($arrayCheckedParams);
            if (true === empty($arrayUserInfo)) {
                $arrayErrorInfo = trans('api_msg.fail');
                $arrayErrors = ['password' => trans('login_msg.password_error')];
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $strToken = (string)Str::uuid();
            $arrayCacheValue = [
                'user_id' => $arrayUserInfo['user_id'],
                'user_name' => $arrayUserInfo['user_name'],
            ];
            $strTokenKey = Config::get('sysconstants.CACHE_INFO.API.PREFIX') . '_' . $strToken;
            Cache::put($strTokenKey, $arrayCacheValue, Config::get('sysconstants.CACHE_INFO.API.EXPIRES'));
            $arrayBack['result']['token'] = $strToken;
            $arrayBack['result']['user_info'] = [
                'user_id' => $arrayUserInfo['user_id'],
                'login_id' => $arrayUserInfo['login_id'],
                'user_name' => $arrayUserInfo['user_name'],
            ];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }

    }

    /**
     * @description 退出登录
     * @return
     * @date 2022/7/7 @version v1.0.0 @add shh.ye
     */
    public function logout()
    {
        try {
            $arrayBack = [];
            $arrayErrorInfo = trans('api_msg.success');
            $strDescription = '';
            $strAccessToken = Request::input('access_token');
            $strAccessTokenKey = Config::get('sysconstants.CACHE_INFO.API.PREFIX') . $strAccessToken;

            Cache::forget($strAccessTokenKey);

            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $strDescription);
        } catch (\Throwable $e) {
            CommonLog::writeExceptionLog($e);
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $strDescription);
        }
    }


    /**
     * @description  校验数据
     * @param $arrayParams
     * @param $arrayCheckField
     * @param $arrayErrorInfo
     * @param string $strDescription
     * @param $arrayErrors
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    private function checkParams($arrayParams, $arrayCheckField, &$arrayErrorInfo, &$strDescription = '', &$arrayErrors)
    {
        $arrayRules = [];
        $arrayMessage = [];
        if (true === in_array('login_id', $arrayCheckField, true)) {
            $arrayRules['login_id'] = ['bail', 'required'];
            $arrayMessage['login_id.required'] = trans('login_msg.login_id.required');
        }
        if (true === in_array('password', $arrayCheckField, true)) {
            $arrayRules['password'] = ['bail', 'required'];
            $arrayMessage['password.required'] = trans('login_msg.password.required');
        }
        //手动创建验证器 ---make方法会生成一个新的验证器实例
        //$arrayParams ---期望校验的数据   $arrayRules ---应用到数据上的校验规则     $arrayMessage --- 自定义错误消息
        // 'user_name.required'=>'we neew required'  --- 为给定属性指定自定义消息，采用 . 表示法，先指定属性名称，再指定规则
        $validator = Validator::make($arrayParams, $arrayRules, $arrayMessage);

        //处理错误信息，通过 Validator 实例调用 errors 方法，它会返回一个 Illuminate\Support\MessageBag 实例，
        //该实例包含了各种可以很方便地处理错误信息的方法。并自动给所有视图提供 $errors 变量，也是 MessageBag 类的一个实例。
        if ($validator->fails()) {
            $errors = $validator->errors();  //这是个对象。
            //参数错误
            $arrayErrorInfo = trans('api_msg.parameter_error');
            foreach ($arrayCheckField as $strField) {
                //判断特定字段是否含有错误信息，has方法可用于判断给定字段是否包含任何错误信息  (返回true/false)
                if ($errors->has($strField)) {
                    //使用first方法可检索给定字段的第一个错误信息
                    $arrayErrors[$strField] = $errors->first($strField);
                }
            }
        }
    }
}
