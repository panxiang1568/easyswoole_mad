<?php

namespace App\model;

use App\constant\C;
use App\entity\IBaseInformation;

class Cycle
{
    use CommonTools;

    /**
     * 获取心跳周期
     * 维度优先级 机型>渠道>产品
     * @param IBaseInformation $obj 请求对象
     * @return int 心跳周期（小时）
     */
    public static function get(IBaseInformation $obj): int
    {
        //1、根据请求机型、渠道、产品等信息获取心跳周期，异常或没有结果时，返回8
        //2、默认值配置应该灵活配置在配置文件、redis等地方

        $redis = self::getRedis();

        $appkey = $obj->getAppid();
        $channel = $obj->getChannel();
        $model = $obj->getMod();

        #获取机型心跳周期
        $cycle = $redis->hget('cycle', $appkey.'_'.$channel.'_'.$model);

        #获取渠道心跳周期
        is_null($cycle) &&  $cycle = $redis->hget('cycle', $appkey.'_'.$channel);

        #获取产品心跳周期
        is_null($cycle) && $cycle = $redis->hget('cycle', $appkey);

        return !is_null($cycle) ? $cycle : C::$CYCLE_HOUR;
    }

    //根据swooletable获取数据
//    public static function get(IBaseInformation $obj): int
//    {
//        //1、根据请求机型、渠道、产品等信息获取心跳周期，异常或没有结果时，返回8
//        //2、默认值配置应该灵活配置在配置文件、redis等地方
//
//        //获取Swoole Table 【Cycle】
//        $swooleTable = TableManager::getInstance()->get("cycle");
//
//        $appkey = $obj->getAppid();
//        $channel = $obj->getChannel();
//        $model = $obj->getMod();
//
//        #获取机型心跳周期
//        $cycle = $swooleTable->get($appkey.'_'.$channel.'_'.$model,'cycle');
//
//        #获取渠道心跳周期
//        !$cycle &&  $cycle = $swooleTable->get($appkey.'_'.$channel,'cycle');
//
//        #获取产品心跳周期
//        !$cycle && $cycle = $swooleTable->get($appkey,'cycle');
//
//        return $cycle ? $cycle : C::$CYCLE_HOUR;
//    }

}