<?php
namespace ryan\mq;

use EasySwoole\Component\Pool\PoolObjectInterface;
//use Swoole\Coroutine\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;


/**
 * @method sismember( string $redis_key, string $getDevid )
 */
class RabbitMQObject extends AMQPStreamConnection implements PoolObjectInterface
{

    function gc()
    {
	    try {
		    $this->close();
	    } catch ( \Exception $e ) {
	    }
    }

    function objectRestore()
    {
    }

    function beforeUse(): bool
    {
        return true;
    }

}