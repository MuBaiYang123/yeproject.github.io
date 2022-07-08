<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * @version v1.0.0
 *
 * @description 共通log输出处理
 *
 * @author shh.ye
 */
class CommonLog
{
    /**
     * @description 将错误信息输出到Log日志中
     * @param $strErrMessage
     * @param $strFile
     * @param $intLine
     * @param $strFunction
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public static function writeErrorLog($strErrMessage, $strFile, $intLine, $strFunction)
    {
        //获取共通的log参数
        $arrLogInfo = self::getCommonLogInfo();

        $strOutputTemplate =
            '[SERVERIP %s] [CLIENTIP %s] [ERROR %s] [FILE %s] [LINE %s] [FUNCTION %s]';
        $strMessage = sprintf(
            $strOutputTemplate,
            $arrLogInfo['serverIp'],
            $arrLogInfo['clientIp'],
            $strErrMessage,
            $strFile,
            $intLine,
            $strFunction
        ); //格式化错误字符串
        Log::error($strMessage);
    }

    /**
     * @description 将异常信息输出到Log日志中
     * @param object $objException  异常对象
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public static function writeExceptionLog($objException)
    {
        //获取共通的log参数
        $arrLogInfo = self::getCommonLogInfo();

        $strOutputTemplate =
            '[SERVERIP %s] [CLIENTIP %s] [ERROR %s] [FILE %s] [LINE %s] [FUNCTION %s]';
        $strExcepMess = $objException->getMessage();
        $arrTrace = $objException->getTrace();
        $strFile = isset($arrTrace[0]['file']) ? $arrTrace[0]['file'] : '';
        $intLine = isset($arrTrace[0]['line']) ? $arrTrace[0]['line'] : '';
        $strFunction = isset($arrTrace[0]['function']) ? $arrTrace[0]['function'] : '';
        $strMessage = sprintf(
            $strOutputTemplate,
            $arrLogInfo['serverIp'],
            $arrLogInfo['clientIp'],
            $strExcepMess,
            $strFile,
            $intLine,
            $strFunction
        ); //格式化错误字符串
        //输出异常信息
        Log::error($strMessage);
    }

    /**
     * @description 将sql信息输出到log中
     *
     * @param string $strSql sql语句
     * @param array $arrBindings sql中绑定的参数
     * @param decimal $decTime sql语句执行时间:单位ms
     *
     * @date 2020-8-20 @version v1.0.0 @add shh.ye
     */
    public static function writeSqlLog($strSql, $arrBindings, $decTime)
    {
        //获取共通的log参数
        $arrLogInfo = self::getCommonLogInfo();

        $strOutputTemplate = '[SERVERIP %s] [CLIENTIP %s] [SQL execute time "%s"ms] [SQL request "%s"]';
        $strMessage = sprintf(
            $strOutputTemplate,
            $arrLogInfo['serverIp'],
            $arrLogInfo['clientIp'],
            $decTime,
            $strSql
        );
        //输出sql log
        Log::info($strMessage, $arrBindings);
    }

    /**
     * @description 将访问页面的信息输出到log中
     *
     * @param string $strPageUrl 访问页面的URL
     *
     * @date 2020-8-20 @version v1.0.0 @add shh.ye
     */
    public static function writeAccessLog($strPageUrl)
    {
        //获取共通的log参数
        $arrLogInfo = self::getCommonLogInfo();

        $strOutputTemplate = '[SERVERIP %s] [CLIENTIP %s] [Access page - %s]';
        $strMessage = sprintf(
            $strOutputTemplate,
            $arrLogInfo['serverIp'],
            $arrLogInfo['clientIp'],
            $strPageUrl
        );
        //输出page access log
        Log::info($strMessage);
    }

    /**
     * @description 将信息输出到Log日志中
     *
     * @param string $strErrMessage
     * @param string $strFile
     * @param int $intLine
     * @param string $strFunction
     *
     * @date 2020-8-20 @version v1.0.0 @add shh.ye
     */
    public static function writeInfoLog($strInfoMessage, $strFile, $intLine, $strFunction)
    {
        //获取共通的log参数
        $arrLogInfo = self::getCommonLogInfo();
        $strOutputTemplate =
            '[SERVERIP %s] [CLIENTIP %s] [INFO %s] [FILE %s] [LINE %s] [FUNCTION %s]';
        $strMessage = sprintf(
            $strOutputTemplate,
            "",
            "",
            $arrLogInfo['serverIp'],
            $arrLogInfo['clientIp'],
            $strInfoMessage,
            $strFile,
            $intLine,
            $strFunction
        ); //格式化错误字符串
        Log::info($strMessage);
    }


    /**
     * @description 输出log时,取得需要输出的共通信息:SERVERIP,CLIENTIP,
     *
     * @return array
     *
     * @date 2020-8-20 @version v1.0.0 @add shh.ye
     */
    public static function getCommonLogInfo()
    {
        //获取SERVERIP和CLIENTIP
        $strServerIp = '';
        $strClientIp = '';
        if (true === isset($_SERVER['SERVER_ADDR'])) {
            $strServerIp = $_SERVER['SERVER_ADDR'];
            $strClientIp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR'];
        }

        //返回结果
        $arrayReturn = array(
            'serverIp' => $strServerIp,
            'clientIp' => $strClientIp,
        );
        return $arrayReturn;
    }



}

