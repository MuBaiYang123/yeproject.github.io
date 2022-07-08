<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resources extends Model
{
    /**
     * @description  获取资源列表 ---前十条语句
     * @param $intPage
     * @param $intLimit
     * @return
     * @date 2022/7/7 @version v1.0.0 @add shh.ye
     */
    public static function getResourcesList($intLimit)
    {
        $arrayBind = [
            'limit' => $intLimit
        ];
        $strSql = '
            select
                *
            from
                tb_resources
            order by update_date desc,resource_id desc
            limit :limit
        ';
        $objResourcesList = DB::select($strSql, $arrayBind);
        return json_decode(json_encode($objResourcesList), true);
    }
}
