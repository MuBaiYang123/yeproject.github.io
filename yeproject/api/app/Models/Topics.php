<?php

namespace App\Models;

use App\Helpers\CommUtilFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Topics extends Model
{
    /**
     * @description 新建帖子
     * @param $arrayParams
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function createTopic($arrayParams)
    {
        $strNowDateTime = date('Y-m-d H:i:s', time());
        $arrayBind = [
            'user_id' => $arrayParams['user_id'],
            'category_id' => $arrayParams['category_id'],
            'topic_title' => $arrayParams['topic_title'],
            'topic_content' => $arrayParams['topic_content'],
            'create_date' => $strNowDateTime,
            'update_date' => $strNowDateTime,
        ];
        $strSql = '
            insert
              into
                 tb_topics
              (
               user_id,
               category_id,
               topic_title,
               topic_content,
               create_date,
               update_date
              )
            values
               (
                :user_id,
                :category_id,
                :topic_title,
               :topic_content,
               :create_date,
               :update_date
               )
        ';
        return DB::insert($strSql, $arrayBind);
    }

    /**
     * @description 编辑帖子
     * @param $arrayParams
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function putTopic($arrayParams)
    {
        $arrayBind = [
            'topic_id' => $arrayParams['topic_id'],
            'category_id' => $arrayParams['category_id'],
            'topic_title' => $arrayParams['topic_title'],
            'topic_content' => $arrayParams['topic_content'],
            'update_date' => date('Y-m-d H:i:s', time())
        ];
        $strSql = '
            update
               tb_topics
            set
               category_id =:category_id,
               topic_title =:topic_title,
               topic_content =:topic_content,
               update_date =:update_date
            where topic_id =:topic_id
        ';
        return DB::update($strSql, $arrayBind);
    }

    /**
     * @description  帖子详情
     * @param $arrayParams
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function getTopicInfo($arrayParams)
    {
        $arrayBind = [
            'topic_id' => $arrayParams['topic_id'],
        ];
        $strSql = '
           select
              t.topic_id,
              t.user_id,
              t.category_id,
              t.topic_title,
              t.topic_content,
              t.create_date,
              t.update_date,
              u.user_name
           from
               tb_topics t left join tb_user u on t.user_id = u.user_id
           where t.topic_id =:topic_id
        ';
        $objTopicInfo = DB::selectOne($strSql, $arrayBind);
        return json_decode(json_encode($objTopicInfo), true);
    }

    /**
     * @description  自己发的帖子的列表
     * @param $intPage
     * @param $intLimit
     * @param $arrayParams
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function getSelfTopicList($intPage, $intLimit, $arrayParams)
    {
        $arrayResult = [
            'topics_list' => [],
            'topics_total' => Config::get('sysconstants.NORMAL_NUM.ZERO')
        ];
        $arraySelectBind = [
            'user_id' => $arrayParams['user_id']
        ];
        $strSelectSql = '
            select
                count(*) as topics_count
            from
                tb_topics
            where user_id =:user_id
        ';
        $objSelfTopicTotal = DB::select($strSelectSql, $arraySelectBind);
        $intTopicsTotal = $objSelfTopicTotal[0]->topics_count;
        $intPossiblePage = ceil($intTopicsTotal / $intLimit);
        $arrayResult['topics_total'] = $intTopicsTotal;
        if ($intPossiblePage < $intPage) {
            return $arrayResult;
        }
        $arrayBind = [
            'page' => ($intPage - 1) * $intLimit,
            'limit' => $intLimit,
            'user_id' => $arrayParams['user_id']
        ];
        $strSql = '
            select
                topic_id,
                user_id,
                category_id,
                topic_title,
                topic_content,
                create_date,
                update_date
            from
               tb_topics
            where user_id =:user_id
            order by update_date desc,topic_id desc
            limit :page,:limit
        ';
        $objSelfTopicList = DB::select($strSql, $arrayBind);
        $arrayResult['topics_list'] = json_decode(json_encode($objSelfTopicList), true);
        return $arrayResult;
    }

    /**
     * @description 所有已发布的帖子列表
     * @return
     * @date 2022/7/5 @version v1.0.0 @add shh.ye
     */
    public static function getAllTopicsList($intPage, $intLimit)
    {
        $arrayResult = [
            'topics_list' => [],
            'topics_total' => Config::get('sysconstants.NORMAL_NUM.ZERO')
        ];
        $strSelectSql = '
            select
                count(*) as topics_count
            from
                tb_topics
        ';
        $objSelfTopicTotal = DB::select($strSelectSql);
        $intTopicsTotal = $objSelfTopicTotal[0]->topics_count;
        $intPossiblePage = ceil($intTopicsTotal / $intLimit);
        $arrayResult['topics_total'] = $intTopicsTotal;
        if ($intPossiblePage < $intPage) {
            return $arrayResult;
        }
        $arrayBind = [
            'page' => ($intPage - 1) * $intLimit,
            'limit' => $intLimit,
        ];
        $strSql = '
            select
                t.topic_id,
                t.user_id,
                t.category_id,
                t.topic_title,
                t.topic_content,
                t.create_date,
                t.update_date,
                u.user_name,
                c.category_name
            from
               tb_topics t
                   left join tb_user u on t.user_id = u.user_id
                   left join tb_categories c on t.category_id = c.category_id
            order by t.update_date desc,t.topic_id desc
            limit :page,:limit
        ';
        $objSelfTopicList = DB::select($strSql, $arrayBind);
        $arrayTopicsList = json_decode(json_encode($objSelfTopicList), true);
        foreach ($arrayTopicsList as &$value) {
            $intCreateDateDiff = time() - strtotime($value['create_date']);
            $intDayDiff = ceil($intCreateDateDiff / (Config::get('sysconstants.DAY_DIFF')));
            $value['create_date_diff'] = CommUtilFunction::handleDayDiff($intDayDiff);
        }
        $arrayResult['topics_list'] = $arrayTopicsList;
        return $arrayResult;
    }

}
