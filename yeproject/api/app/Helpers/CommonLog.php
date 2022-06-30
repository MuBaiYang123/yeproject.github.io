<?php


namespace App\Helpers;


/**
 * Class CommonLog  共通log输出处理
 * @package App\Helpers
 */
class CommonLog
{

    






    /**
     * @description  输出log时，取得的需要输出的共通信息：SERVERIP，CLIENTIP
     * @return array
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    public static function getCommonLogInfo()
    {
        //获取SERVERIP 和 CLIENTIP
        $strServerIp = '';
        $strClientIp = '';
        //SERVER_ADDR：服务器地址   HTTP_X_FORWARDED_FOR：浏览当前页面的用户计算机的网关  REMOTE_ADDR:浏览当前页面的用户计算机的ip地址
        //1、若未使用代理，则REMOTE_ADDR取到的就是客户端的IP地址，HTTP_X_FORWARDED_FOR为空
        //2.若使用了代理，则 REMOTE_ADDR 取到的就是代理服务器的IP 地址，并非真实客户端地址，此时，就要使用 HTTP_X_FORWARDED_FOR 来获取真实客户端地址。
        if (true === isset($_SERVER['SERVER_ADDR'])) {
            $strServerIp = $_SERVER['SERVER_ADDR'];
            $strClientIp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        }
        return [
            'serverIp' => $strServerIp,
            'clientIp' => $strClientIp
        ];
    }

}
