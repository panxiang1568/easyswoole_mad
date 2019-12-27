<?php

namespace ryan\cache;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class RedisPool extends AbstractPool
{
    /**
     * 创建redis连接池对象
     * @return RedisObject
     */
    protected function createObject(): RedisObject
    {
        // TODO: Implement createObject() method.
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new RedisObject();
        //设置兼容模式，保证hGetAll等命令redis返回结果与原生redis一致
	    $redis->setOptions(['compatibility_mode' => true]);
        $connected = $redis->connect($conf['host'], $conf['port']);
        if ($connected) {
            if (!empty($conf['auth'])) {
                $redis->auth($conf['auth']);
            }
            //选择数据库,默认为0
            if (!empty($conf['db'])) {
                $redis->select($conf['db']);
            }
            return $redis;
        } else {
            return null;
        }
    }

}