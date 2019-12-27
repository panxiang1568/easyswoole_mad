<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

/**
 * 校验普通任务返回数量
 * Class GeneralFrequencyValidate
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 14:46
 * @package App\logic\validate\task
 */
class GeneralFrequencyValidate
{

    /**
     * 校验规则：
     * 当前任务是普通任务，且没有其他普通任务校验通过
     * @param AppRequestEntity $appRequestEntity 请求实例
     * @param TaskEntity $taskEntity 任务实例
     * @return bool True:校验通过，False：校验不通过
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity)
    {
        if ($taskEntity->getFrequency() == C::$TASK_FREQUENCY_GENERAL && $appRequestEntity->getIsContainGeneral()) {
            return false;
        }
        return true;
    }
}