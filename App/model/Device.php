<?php

namespace App\model;

use App\entity\IAppRequest;
use App\exception\ValidateException;
use Throwable;

class Device
{
	use CommonTools;

	/**
	 * 获取设备信息
	 *
	 * @param IAppRequest $obj 请求对象
	 *
	 * @return array [设备ID，设备注册时间]
	 * @throws Throwable
	 */
    public static function get(IAppRequest $obj): array
    {
        //获取设备ID以及注册时间
	    $redis_obj = self::getRedis();
	    //获取redis中数据并分割为数组
	    $device_info = $redis_obj->hGet('device:' . $obj->getChannelId(), $obj->getDevid());
	    $device_info_arr = explode('|', $device_info);

	    if (!$device_info || count($device_info_arr) < 2) {
	    	throw new ValidateException('device info is not exist', 2000, $obj);
	    }

		return $device_info_arr;
    }

	/**
	 * 获取设备测试状态
	 * @param IAppRequest $obj 请求对象
	 * @return bool True 测试设备，False：非测试设备
	 * @throws Throwable
	 */
    public static function isTest(IAppRequest $obj):bool
    {
        //获取设备测试状态
	    $channel_id = $obj->getChannelId();
	    $redis_key = 'testdev:'.$channel_id;
	    $redis_obj = self::getRedis();

	    return $redis_obj->sismember($redis_key, $obj->getDevid()) ? true : false;
    }

}