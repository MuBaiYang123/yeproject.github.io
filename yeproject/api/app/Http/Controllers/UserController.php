<?php


namespace App\Http\Controllers;


use App\Helpers\CommonResponseFunction;
use App\Helpers\CommUtilFunction;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * @description 用户注册
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public function userRegister()
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
                'password',
                'confirm_password',
                'user_name',
                'tel'
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $arrayCheckedParams = [
                'login_id' => $arrayParams['login_id'],
                'password' => $arrayParams['password'],
                'user_name' => $arrayParams['user_name'],
                'tel' => $arrayParams['tel'],
            ];
            $blnResult = User::userRegister($arrayCheckedParams);
            if (!$blnResult) {
                $arrayErrorInfo = trans('api_msg.fail');
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description 获取user-list
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public function getUserListById()
    {
        $arrayParams = Request::all();
        $arrayCheckedParams = [
            'user_id' => $arrayParams['user_id']
        ];
        $arrayUserList = User::getUserListById($arrayCheckedParams);
        return $arrayUserList;
    }

    /**
     * @description 更新用户信息
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public function putUser()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                'user_name',
                'tel'
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $strAccessToken = Request::input('access_token');
            $arrayCacheValue = CommUtilFunction::getCacheInfoByToken($strAccessToken);
            $arrayCheckedParams = [
                'user_id' => $arrayCacheValue['user_id'],
                'user_name' => $arrayParams['user_name'],
                'tel' => $arrayParams['tel'],
            ];
            $intResult = User::putUser($arrayCheckedParams);
            if (!$intResult) {
                $arrayErrorInfo = trans('api_msg.fail');
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description  删除用户
     * @return
     * @date 2022/7/1 @version v1.0.0 @add shh.ye
     */
    public function deleteUser()
    {
        $arrayParams = Request::all();
        $arrayCheckedParams = [
            'user_id' => $arrayParams['user_id'],
        ];
        $blnResult = User::deleteUser($arrayCheckedParams);
        return [
            'user_id' => $arrayParams['user_id']
        ];

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
            $arrayRules['login_id'] = ['bail', 'required', 'email:rfc,dns', 'unique:tb_user,login_id'];
            $arrayMessage['login_id.required'] = trans('login_msg.login_id.required');
            $arrayMessage['login_id.email'] = trans('login_msg.login_id.email');
            $arrayMessage['login_id.unique'] = trans('login_msg.login_id.unique');
        }
        if (true === in_array('user_name', $arrayCheckField, true)) {
            $arrayRules['user_name'] = ['bail', 'required'];
            $arrayMessage['user_name.required'] = trans('login_msg.user_name.required');
        }
        if (true === in_array('password', $arrayCheckField, true)) {
            $arrayRules['password'] = ['bail', 'required', 'password_check'];
            $arrayMessage['password.required'] = trans('login_msg.password.required');
            $arrayMessage['password.password_check'] = trans('login_msg.password.password_check');
        }
        if (true === in_array('confirm_password', $arrayCheckField, true)) {
            $arrayRules['confirm_password'] = ['bail', 'required', 'password_check', 'same:password'];
            $arrayMessage['confirm_password.required'] = trans('login_msg.confirm_password.required');
            $arrayMessage['confirm_password.password_check'] = trans('login_msg.confirm_password.password_check');
            $arrayMessage['confirm_password.same'] = trans('login_msg.confirm_password.same');
        }
        if (true === in_array('tel', $arrayCheckField, true)) {
            $arrayRules['tel'] = ['bail', 'required', 'tel_check'];
            $arrayMessage['tel.required'] = trans('login_msg.tel.required');
            $arrayMessage['tel.tel_check'] = trans('login_msg.tel.tel_check');
        }
        $validator = Validator::make($arrayParams, $arrayRules, $arrayMessage);
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
