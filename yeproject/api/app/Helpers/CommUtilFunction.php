<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CommUtilFunction
{
    /**
     * @description 获取缓存中的值
     * @param $strToken
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function getCacheInfoByToken($strToken)
    {
        $strTokenKey = Config::get('sysconstants.CACHE_INFO.API.PREFIX') . '_' . $strToken;
        $arrayCacheValue = Cache::get($strTokenKey);
        if (true === empty($arrayCacheValue)) {
            return [];
        }
        return $arrayCacheValue;
    }

    /**
     * @description  转化具体天数为几年、几月、几周、几天
     * @param $intDayDiff
     * @return string
     * @return
     * @date 2022/7/6 @version v1.0.0 @add shh.ye
     */
    public static function handleDayDiff($intDayDiff)
    {
        $strDayDiff = '';
        if ($intDayDiff >= Config::get('sysconstants.YEAR')) {
            $strDayDiff = floor($intDayDiff / Config::get('sysconstants.YEAR')) . trans('common_msg.year');
        }
        if ($intDayDiff >= Config::get('sysconstants.MONTH') && $intDayDiff < Config::get('sysconstants.YEAR')) {
            $strDayDiff = floor($intDayDiff / Config::get('sysconstants.MONTH')) . trans('common_msg.month');
        }
        if ($intDayDiff >= Config::get('sysconstants.WEEK') && $intDayDiff < Config::get('sysconstants.MONTH')) {
            $strDayDiff = floor($intDayDiff / Config::get('sysconstants.WEEK')) . trans('common_msg.week');
        }
        if ($intDayDiff < Config::get('sysconstants.WEEK')) {
            $strDayDiff = $intDayDiff . trans('common_msg.day');
        }
        return $strDayDiff;
    }

    /**
     * @description mysql insert 绑定参数 (批量插入)
     * @param array $arrField 插入的字段
     * @param array $parameter 插入的数据
     * @param array $sign 标识,每次应该都不一样(很重要)
     *
     * @return string
     *
     * @date 2020-3-18 @version v1.0.0 @add shh.ye
     */
    public static function sqlInsert($arrField = [], &$parameter = [], $sign = 0)
    {
        $strSqlInsert = '';
        if (empty($arrField) || empty($parameter) || count($arrField) !== count($parameter))
            return $strSqlInsert;
        foreach ($arrField as $key => &$field) {
            $field .= '_' . $sign;
        }
        $strSqlInsert = implode(',:', $arrField);

        $strSqlInsert = '(:' . $strSqlInsert . '),';
        $parameter = array_combine($arrField, $parameter);

        return $strSqlInsert;
    }

    /**
     * @description  处理sql中的in
     * @param $parameters
     * @param string $keyName
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public static function handleSqlWhereIn($parameters, $arrayBind = [], $keyName = 'parameters')
    {
        $arrayWhereSql = [];
        if ((isset($parameters) && '' !== $parameters) || (true === is_array($parameters) && false === empty($parameters))) {
            if (true === is_array($parameters)) {
                $arrayParams = $parameters;
            } else {
                $arrayParams = explode(',', $parameters);
            }
            foreach ($arrayParams as $key => $val) {
                $strBindKey = $keyName . $key;
                $arrayWhereSql[] = ":" . $strBindKey;
                $arrayBind[$strBindKey] = $val;
            }
        }
        if (false === empty($arrayWhereSql)) {
            $strWhereSql = implode(',', $arrayWhereSql);
        } else {
            $strWhereSql = '';
        }
        return [
            'sql' => $strWhereSql,
            'array_bind' => $arrayBind
        ];
    }


    /**
     * @description mysql where in加工处理(2)
     *
     * @param string $parameter
     *
     * @return array
     *
     * @author shh.ye
     *
     */
    public static function sqlWhereIn($parameter, $keyName = 'parameter')
    {
        $whereSqlAry = [];
        $paramAry = [];
        if ((isset($parameter) && '' != $parameter) || (true === is_array($parameter) && false === empty($parameter))) {
            if (true === is_array($parameter)) {
                $parameterAry = $parameter;
            } else {
                $parameterAry = explode(',', $parameter);
            }
            foreach ($parameterAry as $key => $val) {
                $sqlBindKey = $keyName . $key;
                $whereSqlAry[] = ":" . $sqlBindKey;
                $paramAry[$sqlBindKey] = $val;
            }
        }
        if (false === empty($whereSqlAry)) {
            $whereSql = implode(',', $whereSqlAry);
        } else {
            $whereSql = '';
        }

        return $result[] = [
            'sql' => $whereSql,
            'bindAry' => $paramAry
        ];
    }


}
