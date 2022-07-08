<?php

namespace App\Http\Controllers;

use App\Helpers\CommonResponseFunction;
use App\Helpers\CommUtilFunction;
use App\Models\Topics;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;

class TopicsController extends Controller
{
    /**
     * @description 新建帖子
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public function createTopic()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                'category_id',
                'topic_title',
                'topic_content',
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $strAccessToken = Request::input('access_token');
            $arrayCacheValue = CommUtilFunction::getCacheInfoByToken($strAccessToken);
            $arrayCheckedParams = [
                'user_id' => $arrayCacheValue['user_id'],
                'category_id' => $arrayParams['category_id'],
                'topic_title' => $arrayParams['topic_title'],
                'topic_content' => $arrayParams['topic_content'],
            ];
            $blnResult = Topics::createTopic($arrayCheckedParams);
            if (!$blnResult) {
                $arrayErrorInfo = trans('api_msg.fail');
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description 编辑帖子
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public function putTopic()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                'topic_id',
                'category_id',
                'topic_title',
                'topic_content',
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $arrayCheckedParams = [
                'topic_id' => $arrayParams['topic_id'],
                'category_id' => $arrayParams['category_id'],
                'topic_title' => $arrayParams['topic_title'],
                'topic_content' => $arrayParams['topic_content'],
            ];
            $intResult = Topics::putTopic($arrayCheckedParams);
            if (!$intResult) {
                $arrayErrorInfo = trans('api_msg.fail');
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description 帖子详情
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public function getTopicInfo()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            //要检验的字段 --- 放入该数组中
            $arrayCheckField = [
                'topic_id'
            ];
            //检测参数的方法
            $this->checkParams($arrayParams, $arrayCheckField, $arrayErrorInfo, $strDescription, $arrayErrors);
            if (Config::get('sysconstants.CODE_API_SUCCESS') !== $arrayErrorInfo['code']) {
                //参数错误
                return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
            }
            $arrayCheckedParams = [
                'topic_id' => $arrayParams['topic_id'],
            ];
            $arrayTopicInfo = Topics::getTopicInfo($arrayCheckedParams);
            $arrayBack['result'] = [
                'topic_info' => $arrayTopicInfo
            ];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description 自己发的帖子的列表
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public function getSelfTopicList()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            if (true === isset($arrayParams['page']) && $arrayParams['page'] !== "") {
                $intPage = (int)$arrayParams['page'];
            } else {
                $intPage = Config::get('sysconstants.PAGE_CONFIG.PAGE');
            }
            if (true === isset($arrayParams['limit']) && $arrayParams['limit'] !== "") {
                $intLimit = (int)$arrayParams['limit'];
            } else {
                $intLimit = Config::get('sysconstants.PAGE_CONFIG.LIMIT');
            }
            $strToken = Request::input('access_token');
            $arrayCacheValue = CommUtilFunction::getCacheInfoByToken($strToken);
            $arrayCheckedParams = [
                'user_id' => $arrayCacheValue['user_id'],
            ];
            $arraySelfTopicsList = Topics::getSelfTopicList($intPage, $intLimit, $arrayCheckedParams);
            $arrayBack['result'] = [
                'topic_list' => $arraySelfTopicsList['topics_list'],
                'topic_total' => $arraySelfTopicsList['topics_total']
            ];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }

    /**
     * @description  所有已发布的帖子列表
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public function getAllTopicsList()
    {
        try {
            $arrayErrorInfo = trans('api_msg.success');;
            $arrayBack = [];
            $arrayErrors = [];
            $strDescription = '';
            $arrayParams = Request::input('parameters');
            if (true === isset($arrayParams['page']) && $arrayParams['page'] !== "") {
                $intPage = (int)$arrayParams['page'];
            } else {
                $intPage = Config::get('sysconstants.PAGE_CONFIG.PAGE');
            }
            if (true === isset($arrayParams['limit']) && $arrayParams['limit'] !== "") {
                $intLimit = (int)$arrayParams['limit'];
            } else {
                $intLimit = Config::get('sysconstants.PAGE_CONFIG.LIMIT');
            }
            $arraySelfTopicsList = Topics::getAllTopicsList($intPage, $intLimit);
            $arrayBack['result'] = [
                'topic_list' => $arraySelfTopicsList['topics_list'],
                'topic_total' => $arraySelfTopicsList['topics_total']
            ];
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        } catch (\Throwable $e) {
            $arrayErrorInfo = trans('api_msg.server_error');
            $strDescription = $e->getMessage();
            return CommonResponseFunction::getJsonRespone($arrayErrorInfo, $arrayBack, $arrayErrors, $strDescription);
        }
    }


    /**
     * @description  校验数据
     * @param $arrayParams
     * @param $arrayCheckField
     * @param $arrayErrorInfo
     * @param string $strDescription
     * @param $arrayErrors
     * @return
     * @date 2022/6/30 @version v1.0.0 @add shh.ye
     */
    private function checkParams($arrayParams, $arrayCheckField, &$arrayErrorInfo, &$strDescription = '', &$arrayErrors)
    {
        $arrayRules = [];
        $arrayMessage = [];
        if (true === in_array('category_id', $arrayCheckField, true)) {
            $arrayRules['category_id'] = ['bail', 'required', 'id_check'];
            $arrayMessage['category_id.required'] = trans('topics_msg.category_id.required');
            $arrayMessage['category_id.id_check'] = trans('topics_msg.category_id.required');
        }
        if (true === in_array('topic_title', $arrayCheckField, true)) {
            $arrayRules['topic_title'] = ['bail', 'required'];
            $arrayMessage['topic_title.required'] = trans('topics_msg.topic_title.required');
        }
        if (true === in_array('topic_content', $arrayCheckField, true)) {
            $arrayRules['topic_content'] = ['bail', 'required'];
            $arrayMessage['topic_content.required'] = trans('topics_msg.topic_content.required');
        }
        if (true === in_array('topic_id', $arrayCheckField, true)) {
            $arrayRules['topic_id'] = ['bail', 'required', 'id_check'];
            $arrayMessage['topic_id.required'] = trans('topics_msg.topic_id.required');
            $arrayMessage['topic_id.id_check'] = trans('topics_msg.topic_id.required');
        }
        //手动创建验证器 ---make方法会生成一个新的验证器实例
        //$arrayParams ---期望校验的数据   $arrayRules ---应用到数据上的校验规则     $arrayMessage --- 自定义错误消息
        // 'user_name.required'=>'we neew required'  --- 为给定属性指定自定义消息，采用 . 表示法，先指定属性名称，再指定规则

        $validator = Validator::make($arrayParams, $arrayRules, $arrayMessage);

        //处理错误信息，通过 Validator 实例调用 errors 方法，它会返回一个 Illuminate\Support\MessageBag 实例，
        //该实例包含了各种可以很方便地处理错误信息的方法。并自动给所有视图提供 $errors 变量，也是 MessageBag 类的一个实例。
        if ($validator->fails()) {
            $errors = $validator->errors();  //这是个对象。
            //参数错误
            $arrayErrorInfo = trans('api_msg.parameter_error');
            foreach ($arrayCheckField as $strField) {
                //判断特定字段是否含有错误信息，has方法可用于判断给定字段是否包含任何错误信息  (返回true/false)
                if ($errors->has($strField)) {
                    //使用first方法可检索给定字段的第一个错误信息
                    $arrayErrors[$strField] = $errors->first($strField);
                }
            }
        }
    }
}
