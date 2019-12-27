<?php

namespace App\model;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\IAppRequest;
use App\entity\TaskEntity;
use App\exception\ValidateException;
use App\logic\validate\RedisTaskInfoValidate;

/**
 * Class Task
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 18:02
 * @package App\model
 */
class Task
{

    use CommonTools;
	/**
	 * 根据状态获取任务id集合
	 *
	 * @param string|null $status 任务状态
	 * @param IAppRequest $obj
	 *
	 * @return array 任务ID集合
	 */

    public static function getIdsByStatus(?string $status, IAppRequest $obj): array
    {
        //根据状态获取任务id集合
        $redis = self::getRedis();

        $channel_id = $obj->getChannelId();

        switch ($status) {
            case C::$TASK_STATUS_TESTING:
                $key = 'task_list:test';
                $taskIds = $redis->hget($key, $channel_id);
                break;
            case C::$TASK_STATUS_ONLINE:
                $key = 'task_list:online';
                $taskIds = $redis->hget($key, $channel_id);
                break;
            default:
                $taskIds = [];
                break;
        }

        if(!$taskIds){

            throw new ValidateException("taskIds is null", 2003, $obj);

        }


        return explode(',',$taskIds);
    }

	/**
	 * 根据任务ID获取任务实例
	 *
	 * @param $taskId
	 *
	 * @return TaskEntity
	 */
    public static function get( $taskId): TaskEntity
    {
        //根据ID从Redis获取任务
		$redis_obj = self::getRedis();
        $task_info_array = $redis_obj->hGetAll('task_info:' . $taskId);

        RedisTaskInfoValidate::checkTaskInfo($task_info_array);
        //生成Task Entity
        $taskEntity = new TaskEntity();
        $taskEntity->unserialize($task_info_array);

        return $taskEntity;
    }

    /**
     * 根据任务ID和设备IMEI判断是否拉取过该任务
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return void
     */
    public static function isPulled(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity): void
    {
        $redis = self::getRedis();

        $devid = $appRequestEntity->getDeviceId();
        $taskId = $taskEntity->getId();
        $task_pull = $redis->hget('task_pull_history:'.$taskId, $devid);
        if($task_pull){
            $taskEntity->setIsPull(True);
            $task_pull = explode('|', $task_pull);
            $taskEntity->setTaskPullTime($task_pull[0]);
            $taskEntity->setTaskPullNum($task_pull[1]);

        }else{
            $taskEntity->setIsPull(False);
        }

    }


}