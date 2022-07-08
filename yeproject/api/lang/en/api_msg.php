<?php
return [
    //公共，特定code码
    'success'                   => ['code' => 20000, 'message' => '成功'],
    'access_token_expired'      => ['code' => 40000, 'message' => 'token 失效'],
    'parameter_missed'          => ['code' => 40010, 'message' => '表单丢失'],
    'parameter_error'           => ['code' => 40011, 'message' => '表单错误'],
    'fail'                      => ['code' => 40012, 'message' => '失败'],
    'server_error'              => ['code' => 50000, 'message' => '服务器错误'],
];
