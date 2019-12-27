<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class ModelValidate implements ITaskValidate
{

    /**
     * 任务机型校验
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {
        $model_rule = $taskEntity->getModelRule();
        $task_model = $taskEntity->getModel();
        $model = $appRequestEntity->getMod();

        if($model_rule == C::$TASK_WHITE && $task_model && !in_array($model, $task_model)) return false;
        if($model_rule == C::$TASK_BLACK && $task_model && in_array($model, $task_model)) return false;

        return true;
    }
}