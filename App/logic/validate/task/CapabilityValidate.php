<?php

namespace App\logic\validate\task;

use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class CapabilityValidate implements ITaskValidate
{

    /**
     * 校验设备能力
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {
        $capability = $appRequestEntity->getCapability();
        $resource = $taskEntity->getResource();

        if(!in_array($resource, $capability)) return false;

        return true;
    }
}