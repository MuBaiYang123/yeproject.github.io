<?php


namespace App\Http\Controllers;


use App\Helpers\CommonResponseFunction;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class MyTestController extends Controller
{
    public function myTest()
    {
        $arrayParams = [
          'user_id'=>2,
            'email'=>'m.yang@startiasoft.com',
            'age'=>20
        ];
        $blnResult = User::getUserInfo($arrayParams);
        return $blnResult;
    }


    /**
     * @description  复制模板
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @return
     * @date 2022/7/1 @version v1.0.0 @add shh.ye
     */
    public function template()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                '',
                ''
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $arrayCheckedParams = [
                '' => $arrayParams[''],
            ];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

}
