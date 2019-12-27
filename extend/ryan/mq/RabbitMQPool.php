<?php

namespace ryan\mq;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;
use PhpAmqpLib\Channel\AMQPChannel;

class RabbitMQPool extends AbstractPool
{
	/**
	 * 创建RabbitMQ连接池对象
	 * @return AMQPChannel
	 */
    protected function createObject(): AMQPChannel
    {
        $conf = Config::getInstance()->getConf('RabbitMQ');
        $mq = new RabbitMQObject($conf['host'], $conf['port'], $conf['username'], $conf['password']);
        $channel = $mq->channel();
	    $channel->queue_declare('statistic_pull_log', false,
		    false, false, false);
		return $channel;
    }

}