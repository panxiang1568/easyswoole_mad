<?php


namespace App\model;


use App\entity\IAppRequest;

class Group
{
    use CommonTools;

    /**
     * 获取设备所属分组信息
     *
     * @param IAppRequest $obj 请求对象
     * @return string
     */
    public static function get(IAppRequest $obj): string
    {
        //获取设备所属分组信息
        $redis_obj = self::getRedis();
        $groupIds = $redis_obj->hGet('group_device', $obj->getDevid());

        return $groupIds ?? '';
    }
}