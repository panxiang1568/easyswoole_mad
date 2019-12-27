<?php

namespace App\utils;
use EasySwoole\Component\TableManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\RedisPool\Redis;
use Swoole\Table;

class Init
{
    public static function run(){

        TableManager::getInstance()->add("country",[
            'id'=>['type'=>Table::TYPE_INT,'size'=>2],
            'country'=>['type'=>Table::TYPE_STRING,'size'=>2],
        ]);

        TableManager::getInstance()->add("cycle",[
            'cycle'=>['type'=>Table::TYPE_INT,'size'=>2],
        ]);

        TableManager::getInstance()->get("cycle")->set('1_m631',[
            'cycle'=>1
        ]);

        TableManager::getInstance()->get("cycle")->set('1',[
            'cycle'=>2
        ]);

        TableManager::getInstance()->get("cycle")->set('lk62xlnkap3fd3a3mnss5gnm',[
            'cycle'=>3
        ]);

        TableManager::getInstance()->get("country")->set('CN',[
            'id'=>1,
            'country'=>'CN'
        ]);





        $redisConfigData = Config::getInstance()->getConf('REDIS');
        $redisConfig = new \EasySwoole\RedisPool\Config($redisConfigData);
        // $config->setOptions(['serialize'=>true]);
        $redisPoolConf = Redis::getInstance()->register('redis',$redisConfig);
        $redisPoolConf->setMaxObjectNum($redisConfigData['maxObjectNum']);
        $redisPoolConf->setMinObjectNum($redisConfigData['minObjectNum']);

//        $mysqlConfigData = Config::getInstance()->getConf('MYSQL');
//        $mysqlConfig = new \EasySwoole\Mysqli\Config($mysqlConfigData);
        // $config->setOptions(['serialize'=>true]);
//        $mysqlPoolConf = Mysql::getInstance()->register('mysql',$mysqlConfig);
//        $mysqlPoolConf->setMaxObjectNum($mysqlConfigData['maxObjectNum']);
//        $mysqlPoolConf->setMinObjectNum($mysqlConfigData['minObjectNum']);





    }
}