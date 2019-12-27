<?php


namespace App\model;


use App\entity\IAppRequest;

class TaskDevice
{
    use CommonTools;

    /**
     * 获取设备所属任务信息
     * @param IAppRequest $obj 请求对象
     * @return string
     */
    public static function get(IAppRequest $obj): string
    {
        //获取设备所属分组信息
        $redis_obj = self::getRedis();
        $taskIds = $redis_obj->hGet('task_device', $obj->getDevid());

        return $taskIds ?? '';

    }

}