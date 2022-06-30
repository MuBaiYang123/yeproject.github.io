<?php

namespace App\Http\Controllers;

use App\Helpers\CommonResponseFunction;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login()
    {
        try{
            $arrayErrorInfo = [];
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input();
            $arrayField = [

            ];

            $arrayCheckedParams = [
                'user_name' => $arrayParams['user_name'],
                'password' => $arrayParams['password']
            ];
            $arrayUserInfo = User::getUserInfoByUserName($arrayCheckedParams);
            $arrayResult = [
                'token' => '',
                'user_name' => ''
            ];
            if (true === empty($arrayUserInfo)) {
                return $arrayResult;
            }
            $strToken = (string)Str::uuid();
            $arrayCahcheValue = [
                'user_id'=>  $arrayUserInfo['user_id'],
                'user_name'=>  $arrayUserInfo['user_name'],
            ];
            Cache::put($strToken,$arrayCahcheValue,'600');
            $arrayBack['result']['token'] = $strToken;
            $arrayBack['result']['user_info'] = $arrayUserInfo['user_name'];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo,$arrayBack,$arrayErrors,$strDescription);
        }catch (\Throwable $e){
            $arrayErrorInfo = [];
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo,$arrayBack,$arrayErrors,$strDescription);
        }

    }
}
