<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//普通非登录
Route::prefix('v1')->middleware('decode.parameters')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/user', [UserController::class, 'userRegister']);  //用户注册
    Route::post('/logout', [LoginController::class, 'logout']);  // 退出登录

    //所有资源列表-- 仅取前十
    Route::get('/resources-list',[ResourcesController::class,'getResourcesList']);

    //所有帖子列表 --- 按照分类展示（categroy_id区分）

    //所有帖子列表 --- 无任何区分
    Route::get('/topics-list',[TopicsController::class,'getAllTopicsList']);

    //所有留言的帖子列表

});
//普通登录
Route::prefix('v1')->middleware(['check.login','decode.parameters'])->group(function (){


//    Route::get('/user-list', [UserController::class, 'getUserListById']);
    Route::put('/user', [UserController::class, 'putUser']);  //用户自己更新自己的信息

    /**
     * 帖子管理 --- topics
     */
    Route::post('/topic',[TopicsController::class,'createTopic']);  //新建帖子
    Route::put('/topic',[TopicsController::class,'putTopic']);  //修改帖子
    Route::get('/topic',[TopicsController::class,'getTopicInfo']);  //帖子详情
    Route::get('/self-topic-list',[TopicsController::class,'getSelfTopicList']);  //自己发的帖子的列表

    /**
     * 回复管理
     */

});




