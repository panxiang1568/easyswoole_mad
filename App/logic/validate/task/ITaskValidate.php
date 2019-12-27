<?php


namespace App\logic\validate\task;


use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

interface ITaskValidate
{
    /**
     * 校验规则
     * @param AppRequestEntity $appRequestEntity 请求实例
     * @param TaskEntity $taskEntity 任务实例
     * @return bool True:校验通过，False：校验不通过
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity): bool;
}