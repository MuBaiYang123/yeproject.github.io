<?php
return [
    //正确的code码
    "CODE_API_SUCCESS" => 20000,

    //cache中存放信息的key值及前缀固定部分和失效时间
    'CACHE_INFO' => [
        'API' => ['PREFIX' => 'token_api', 'USER_PREFIX' => 'token_user_id', 'EXPIRES' => 7 * 24 * 60 * 60],//token_api_{$strToken}  token_user_id_{login_id}
    ],

    //可调用api的接口类型
    'ACCESS_SYSTEM' => [
        'WEB' => 'web',
        'IOS' => 'ios',
        'ANDROID' => 'android'
    ],

    //校验密码的正则
    'PASSWORD_REG' => '/^[a-zA-Z0-9]{8,20}$/',

    //校验手机号的正则
    'TEL_REG' => '/^1[3-9]\d{9}$/', //手机号第一位是1，第二为是3-9，第三位只要是数字就成。

    //常用数字定义
    'NORMAL_NUM' => [
        'ZERO' => 0,
        'ONE' => 1,
    ],

    //分页配置
    'PAGE_CONFIG' => [
        'PAGE' => 1,
        'LIMIT' => 15
    ],

    //计算天数
    'DAY_DIFF' => 24 * 60 * 60,
    'YEAR' => 365,
    'MONTH' => 30,
    'WEEK' => 7,

];
