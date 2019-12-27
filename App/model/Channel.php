<?php

namespace App\model;

use App\entity\IBaseInformation;
use App\exception\ValidateException;

class Channel
{

    use CommonTools;

	/**
	 * 获取渠道ID
	 *
	 * @param IBaseInformation $obj 请求对象
	 *
	 * @return int [渠道ID]
	 */

    public static function get(IBaseInformation $obj): int
    {

        //获取渠道ID
        $redis = self::getRedis();

        $appkey = $obj->getAppid();
        $channel = $obj->getChannel();

        $key = 'appkey:'.$appkey;

        $channel_id = $redis->hget($key, $channel);

        if (!$channel_id) {
            throw new ValidateException("invalid channel or channel is not available", 2001, $obj);
        }

        return $channel_id;
    }

}