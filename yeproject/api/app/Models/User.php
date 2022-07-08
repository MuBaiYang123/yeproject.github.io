<?php

namespace App\Models;

use App\Helpers\CommUtilFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class User extends Model
{
    public static function getUserInfo($arrayParams)
    {
        $arrayBind = [
            'age' => $arrayParams['age']
        ];
        $strSql = '
           select
             *
           from
              tb_user
          where age = :age
        ';
        $objUserInfo = DB::selectOne($strSql, $arrayBind);
        $arrayUserInfo = json_decode(json_encode($objUserInfo), true);
        return $arrayUserInfo;
    }

    public static function updateInfo($arrayParams)
    {
        $arrayBind = [
            'email' => $arrayParams['email'],
            'user_id' => $arrayParams['user_id'],
            'age' => $arrayParams['age']
        ];
        $strWhereSql = "or age!=:age";
        $strSql = '
            update
                tb_user
            set
              email=CONCAT("-", :email)
            where   user_id =:user_id ' . $strWhereSql . '
        ';

        return DB::update($strSql, $arrayBind);
    }

    /**
     * @description 匹配登录账号信息
     * @param $arrayParams
     * @return
     * @date 2022/6/28 @version v1.0.0 @add shh.ye
     */
    public static function getUserInfoByUserName($arrayParams)
    {
        $arrayBind = [
            'login_id' => $arrayParams['login_id'],
            'password' => $arrayParams['password']
        ];
        $strSql = '
            select
               *
            from
                tb_user
            where login_id =:login_id and password =:password
        ';
        $objUserInfo = DB::selectOne($strSql, $arrayBind);
        return json_decode(json_encode($objUserInfo), true);
    }


    /**
     * @description 用户注册
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public static function userRegister($arrayParams)
    {
        $strNowDateTime = date('Y-m-d H:i:s',time());
        $arrayBind = [
            'login_id' => $arrayParams['login_id'],
            'password' => $arrayParams['password'],
            'user_name' => $arrayParams['user_name'],
            'tel' => $arrayParams['tel'],
            'update_date' => $strNowDateTime,
            'create_date' => $strNowDateTime,
        ];
        $strSql = '
           insert into
                tb_user
              (
               login_id,
               user_name,
               password,
               tel,
               update_date,
               create_date
              )
              values
                 (
                  :login_id,
                  :user_name,
                  :password,
                  :tel,
                  :update_date,
                  :create_date
                 )
       ';
        return DB::insert($strSql, $arrayBind);
    }

    /**
     * @description 更新用户
     * @param $arrayParams
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public static function putUser($arrayParams)
    {
        $arrayBind = [
            'user_id' => $arrayParams['user_id'],
            'user_name' => $arrayParams['user_name'],
            'tel' => $arrayParams['tel'],
            'update_date' => date('Y-m-d H:i:s', time()),
        ];
        $strSql = '
            update
                tb_user
            set
               user_name =:user_name,
               tel =:tel,
               update_date =:update_date
            where user_id =:user_id
        ';
        return DB::update($strSql,$arrayBind);
    }

    /**
     * @description  测试in
     * @param $arrayParams
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public static function getUserListById($arrayParams)
    {
        $arrayBind = [];
        $arrayResult = CommUtilFunction::handleSqlWhereIn($arrayParams['user_id'], $arrayBind, 'user_id');
        $strWhereSql = $arrayResult['sql'];
        $arrayBind = $arrayResult['array_bind'];
        $strSql = '
            select
               *
             from tb_user
             where user_id in (' . $strWhereSql . ')
        ';
        $objUserList = DB::select($strSql, $arrayBind);
        $arrayUserList = json_decode(json_encode($objUserList), true);
        return $arrayUserList;
    }

    /**
     * @description 删除用户
     * @param $arrayParams
     * @return
     * @date 2022/7/1 @version v1.0.0 @add shh.ye
     */
    public static function deleteUser($arrayParams)
    {
        $arrayBind = [
            'user_id' => $arrayParams['user_id'],
        ];
        $strSql = '
            delete
               from tb_user
            where user_id =:user_id
        ';
        return DB::delete($strSql,$arrayBind);

    }


}
