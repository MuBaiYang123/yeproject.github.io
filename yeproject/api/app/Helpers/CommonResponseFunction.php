<?php


namespace App\Helpers;


class CommonResponseFunction
{
    /**
     * @description 后端数据统一返回格式
     * @param $arrayErrorInfo
     * @param array $arrayBack
     * @param array $arrayErrors
     * @param string $strDescription
     * @return
     * @date 2022/6/29 @version v1.0.0 @add shh.ye
     */
    public static function getJsonRespone($arrayErrorInfo, $arrayBack = [], $arrayErrors = [], $strDescription = '')
    {
        $arrayErrorInfo['description'] = $strDescription;
        $arrayBack['access_status'] = $arrayErrorInfo;
        if (false === empty($arrayErrors)) {
            $arrayBack['result']['errors'] = $arrayErrors;
        }
        return response(json_encode($arrayBack));
    }

}
