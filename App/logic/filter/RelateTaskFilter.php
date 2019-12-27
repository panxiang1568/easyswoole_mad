<?php

namespace App\logic\filter;
use App\entity\IAppRequest;
use App\exception\ValidateException;
use App\model\CommonTools;
use Throwable;

/**
 * 过滤关联任务组
 * Class RelateTaskFilter
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 18:13
 * @package App\logic\filter
 */
class RelateTaskFilter
{

    use CommonTools;

    /**
     * 以关联任务组过滤当前的任务id集合
     * @param array|null $taskIds
     * @param IAppRequest $obj
     * @param array|null $relateIds
     * @return array
     * @throws Throwable
     */

    public static function run(?array $taskIds, IAppRequest $obj, ?array $relateIds): array
    {
        //以关联任务组过滤当前的任务id集合，参考关联任务过滤子流程
        //如果存在关联任务组集，则循环处理。不存在则直接返回taskIDs
        if($relateIds){
            $taskOldIds = $taskIds;
            //循环关联任务组集
            foreach ($relateIds as $relateRow){
                //发布中任务和关联任务组取交集
                $relateTask = array_intersect($taskIds, $relateRow);
                //从任务集合中去掉关联组中的任务
                $taskIds = array_diff($taskIds, $relateRow);
                if($relateTask){
                    $releaseTask = RelateTaskFilter::checkRelateRow($relateTask, $relateRow, $obj);
                    //将关联组中符合条件的任务加到任务集合中
                    $releaseTask && $taskIds[] = $releaseTask;
                }

                $taskIds = array_intersect($taskOldIds, $taskIds);
            }
        }

        if(!$taskIds){
            throw new ValidateException("taskIds is null", 2003, $obj);
        }
        return array_unique($taskIds);
    }

    /**
     * 循环判断关联任务组，获取符合条件的任务ID
     * @param array|null $relateTask
     * @param array|null $relateRow
     * @param IAppRequest $obj
     * @return int
     * @throws Throwable
     */
    public static function checkRelateRow(?array $relateTask, ?array $relateRow, IAppRequest $obj): int
    {

        $releaseTask = 0;
        $redis = self::getRedis();
        //设备是否拉取过关联组中的任务
        $is_pull = false;
        rsort($relateRow);

        //判断设备是否拉取过关联组中的任务
        foreach ($relateRow as $taskId){
            $key = 'task_pull_history:'.$taskId;
            //判断关联组中是否有任务已经被拉取过
            if($redis->hget($key, $obj->getDeviceId())){
                $is_pull = true;
                //判断已经拉取过的任务是否处于发布或测试状态
                if(in_array($taskId, $relateTask)){
                    $releaseTask = $taskId;
                }
                break;
            }
        }

        //如果未拉取过关联组中的任务，返回发布量未满的ID最大的发布或测试状态的任务
        if(!$is_pull){
            rsort($relateTask);
            foreach ($relateTask as $taskId){
                $key = 'task_info:'.$taskId;
                $max_num = $redis->hget($key, 'max_num');
                $cur_num = $redis->hget($key, 'cur_num');

                if($max_num > $cur_num){
                    $releaseTask = $taskId;
                    break;
                }
            }
        }
        return $releaseTask;
    }

}