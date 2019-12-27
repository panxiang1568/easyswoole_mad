<?php

namespace App\model;

use App\constant\C;
use App\entity\IAppRequest;

/**
 * Class Silent
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 15:58
 * @package App\model
 */
class Silent
{

    use CommonTools;

	/**
	 * 获取静默期
	 * 维度优先级 机型>渠道>产品
	 *
	 * @param IAppRequest $obj 请求对象
	 *
	 * @return int 静默天数
	 */
    public static function get(IAppRequest $obj): int
    {

        //1、根据请求机型、渠道、产品获取静默期(天)，异常或没有结果时，返回90
        //2、默认值配置应该灵活配置在配置文件、redis等地方

        $redis = self::getRedis();

        $appkey = $obj->getAppid();
        $channel = $obj->getChannel();
        $model = $obj->getMod();

        #获取机型心跳周期
        $silent = $redis->hget('silent', $appkey.'_'.$channel.'_'.$model);
        
        #获取渠道心跳周期
        is_null($silent) &&  $silent = $redis->hget('silent', $appkey.'_'.$channel);

        #获取产品心跳周期
        is_null($silent) && $silent = $redis->hget('silent', $appkey);

        return !is_null($silent) ? $silent : C::$SILENT_DAY;

    }

//    public static function get(IAppRequest $obj): int
//    {
//
//        //1、根据请求机型、渠道、产品获取静默期(天)，异常或没有结果时，返回90
//        //2、默认值配置应该灵活配置在配置文件、redis等地方
//
//        //获取Swoole Table 【Silent】
//        $swooleTable = TableManager::getInstance()->get("silent");
//
//        $appkey = $obj->getAppid();
//        $channel_id = $obj->getChannelId();
//        $model = $obj->getMod();
//
//        #获取机型心跳周期
//        $silent = $swooleTable->get($channel_id.'_'.$model,'silent');
//
//        #获取渠道心跳周期
//        !$silent &&  $silent = $swooleTable->get($channel_id,'silent');
//
//        #获取产品心跳周期
//        !$silent && $silent = $swooleTable->get($appkey,'silent');
//
//        return $silent ? $silent : C::$SILENT_DAY;
//
//    }

}