<?php

namespace App\logic\validate\task;
use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\exception\UpgradeException;
use App\model\CommonTools;

class TouchValidate implements ITaskValidate
{

    use CommonTools;

    /**
     * 校验规则
     * @param AppRequestEntity $appRequestEntity 请求实例
     * @param TaskEntity $taskEntity 任务实例
     * @return bool True:校验通过，False：校验不通过
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity): bool
    {
        // 1、再次校验触达量
        // 设备未拉取过该任务，且任务触达量+1<发布量，则触达量+1。
        // 2、更新任务拉取时间 首次时间和最后一次拉取时间要区分开
        // 3、普通任务，setIsContainGeneral = true

        $task_id = $taskEntity->getId();
        $cur_num = $taskEntity->getCurNum();
        $max_num = $taskEntity->getMaxNum();
        $is_pull = $taskEntity->getIsPull();
        $frequency = $taskEntity->getFrequency();
        $device_id = $appRequestEntity->getDeviceId();
        $resource = $taskEntity->getResource();
        $redis = self::getRedis();

        //设备未拉取过该任务，且任务触达量+1 > 发布量 不下发
        if(!$is_pull && ($cur_num+1 > $max_num)) return false;

        if($is_pull && ($frequency == C::$TASK_FREQUENCY_MULTI)){

            //已经拉取过的多次激活任务，只更改拉取次数
            $task_pull_time = $taskEntity->getTaskPullTime();
            $interval = floor((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d',$task_pull_time))) / 86400);
            $redis->hset('task_pull_history:'.$task_id, $device_id, $task_pull_time.'|'.$interval);
        }else{

            //未拉取过该任务或者任务类型不是多次激活，更改拉取时间
            $redis->hset('task_pull_history:'.$task_id, $device_id, time().'|0');
            //未拉取过该任务，触达量增1
            !$is_pull && $redis->hset('task_info:'.$task_id, 'cur_num', $cur_num+1);

        }

        //任务为普通任务并且任务未被拉取过，setIsContainGeneral = true
        if(!$is_pull && ($frequency == C::$TASK_FREQUENCY_GENERAL)) $appRequestEntity->setIsContainGeneral(True);

        if($resource == C::$RESOURCE_UPGRADE){
            throw new UpgradeException("task resource is upgrade", 200 );

        }

        return true;
    }
}