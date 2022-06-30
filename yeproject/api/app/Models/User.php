<?php

namespace App\Models;

use App\Helpers\CommUtilFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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
            'user_name' => $arrayParams['user_name'],
            'password' => $arrayParams['password']
        ];
        $strSql = '
            select
               *
            from
                tb_user
            where user_name =:user_name and password =:password
        ';
        $objUserInfo = DB::selectOne($strSql, $arrayBind);
        $arrayUserInfo = json_decode(json_encode($objUserInfo), true);
        return $arrayUserInfo;
    }





    /**
     * @description 批量插入
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public static function createUser($arrayParams)
    {
        $arrayFiled = [
          'user_name',
          'password',
          'email',
          'age',
          'update_date',
          'create_date',
        ];
        $arrayUserName = explode(',',$arrayParams['user_name']);
        $arrayEmail = explode(',',$arrayParams['email']);
        $arrayAge = explode(',',$arrayParams['age']);
        $intInsertNum = count($arrayUserName);
        $arrayBind = [];
        $strInsertSql = '';
        for($i=0;$i<$intInsertNum;$i++){
            $arrayParameters = [
                'user_name'=>$arrayUserName[$i],
                'password'=>'123456',
                'email'=>$arrayEmail[$i],
                'age'=>$arrayAge[$i],
                'update_date'=>date('Y-m-d H:i:s',time()),
                'create_date'=>date('Y-m-d H:i:s',time()),
            ];
            $strInsertPartSql = CommUtilFunction::sqlInsert($arrayFiled,$arrayParameters,$i);
            $arrayBind += $arrayParameters;
            $strInsertSql .=$strInsertPartSql;
        }
        $strPlaceNum = strripos($strInsertSql,')',0);
        $strInsertSql =substr($strInsertSql,0,$strPlaceNum+1);
       $strSql = '
           insert into
                tb_user
              (
               user_name,
               password,
               email,
               age,
               update_date,
               create_date
              )
              values
                 '.$strInsertSql.'
       ';
        return DB::insert($strSql,$arrayBind);
    }

    /**
     * @description  测试in
     * @param $arrayParams
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public  static function  getUserListById($arrayParams)
    {
        $arrayBind =[];
        $arrayResult = CommUtilFunction::handleSqlWhereIn($arrayParams['user_id'], $arrayBind, 'user_id');
        $strWhereSql = $arrayResult['sql'];
        $arrayBind = $arrayResult['array_bind'];
        $strSql = '
            select
               *
             from tb_user
             where user_id in ('.$strWhereSql.')
        ';
        $objUserList = DB::select($strSql,$arrayBind);
        $arrayUserList = json_decode(json_encode($objUserList),true);
        return $arrayUserList;
    }


}
