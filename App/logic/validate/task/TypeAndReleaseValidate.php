<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\model\Task;

class TypeAndReleaseValidate implements ITaskValidate
{

    /**
     * 校验任务类型和发布量
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {
        //任务频次类型
        $frequency = $taskEntity->getFrequency();
        $max_num = $taskEntity->getMaxNum();
        $cur_num = $taskEntity->getCurNum();
        Task::isPulled($appRequestEntity, $taskEntity);
        $is_pull = $taskEntity->getIsPull();
        //未拉取过该任务 && 触达量大于发布量
        if(!$is_pull && ($max_num <= $cur_num)) return false;
        //普通任务已经拉取过
        if(($frequency == C::$TASK_FREQUENCY_GENERAL) && $is_pull) return false;

        if($is_pull){

            $task_pull_time = $taskEntity->getTaskPullTime();
            $task_pull_num = $taskEntity->getTaskPullNum();
            $intervals = $taskEntity->getInterval();
            $interval = (strtotime(date('Y-m-d')) - strtotime(date('Y-m-d',$task_pull_time))) / 86400;

            //多次激活任务 时间间隔不在规定范围内
            if(($frequency == C::$TASK_FREQUENCY_MULTI) && !in_array($interval, $intervals)) return false;
            //间隔拉取任务 间隔时间小于规定时间
            if(($frequency == C::$TASK_FREQUENCY_INTERVAL) && ($interval < $intervals[0])) return false;
            //多次激活任务当天已经拉取过
            if(($frequency == C::$TASK_FREQUENCY_MULTI) && ($task_pull_num == $interval)) return false;

        }

        return true;
    }
}