<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class AreaValidate implements ITaskValidate
{

    /**
     * 校验任务地域性
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {

        //设备IP解析的国家编码
        $country = $appRequestEntity->getCountry();
        //任务的地域
        $task_area = $taskEntity->getArea();

        //任务国内，设备海外 返回false
        if(($task_area == C::$TASK_AREA_DOMESTIC) && ($country != C::$CHINA_CODE)) return false;
        //任务海外，设备国内 返回false
        if(($task_area == C::$TASK_AREA_OVERSEA) && ($country == C::$CHINA_CODE)) return false;

        return true;
    }
}