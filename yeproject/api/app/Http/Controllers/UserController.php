<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    /**
     * @description 创建用户
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public function createUser()
    {
        $arrayParams = Request::all();
        $arrayCheckedParams = [
          'user_name'=>$arrayParams['user_name'],
          'email'=>$arrayParams['email'],
          'age'=>$arrayParams['age'],
        ];
        $blnResult = User::createUser($arrayCheckedParams);
        return true;
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
          'user_id'=>$arrayParams['user_id']
        ];
        $arrayUserList = User::getUserListById($arrayCheckedParams);
        return $arrayUserList;
    }

}
