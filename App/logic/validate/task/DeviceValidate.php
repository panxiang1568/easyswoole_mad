<?php

namespace App\logic\validate\task;

use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class DeviceValidate implements ITaskValidate
{

    /**
     * 分组设备和自定义设备校验
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {

        $task_id = $taskEntity->getId();
        $custom_device = $taskEntity->getCustomDevice();

        //任务的分组ID
        $task_group = $taskEntity->getGroup();
        //设备所属分组ID
        $device_group = $appRequestEntity->getGroupIds();
        //设备所属自注册设备任务ID
        $device_customs = $appRequestEntity->getTaskDeviceIds();

        //既不在分组设备也不在自定义设备中
        if($task_group && $custom_device && !array_intersect($task_group, $device_group) && !in_array($task_id, $device_customs)) return false;
        //有分组设备并且不在分组设备中，不存在自定义设备
        if($task_group && !$custom_device && !array_intersect($task_group, $device_group)) return false;
        //有自定义设备并且不在自定义设备中，不存在分组设备
        if(!$task_group && $custom_device && !in_array($task_id, $device_customs)) return false;

        return true;
    }
}