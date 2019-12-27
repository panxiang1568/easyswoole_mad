<?php

namespace App\model;

/**
 * Class TaskRelate
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 15:58
 * @package App\model
 */
class TaskRelate
{

    use CommonTools;

    /**
     * 获取关联任务集
     * @return array 关联任务集
     */

    public static function get(): array
    {

        //获取关联任务集合

        $redis = self::getRedis();

        $relateIds = $redis->get('task_relate');

        return json_decode($relateIds, true) ?? [];

    }

}