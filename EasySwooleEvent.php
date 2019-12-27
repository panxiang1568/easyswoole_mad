<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Process\HotReload;
use App\utils\Init;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use ryan\cache\RedisPool;
use ryan\mq\RabbitMQPool;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        Di::getInstance()->set(SysConst::HTTP_CONTROLLER_NAMESPACE,'App\\controller\\');//配置控制器命名空间
    }

    public static function mainServerCreate(EventRegister $register)
    {
        PoolManager::getInstance()->register(RedisPool::class);
	    try {
		    PoolManager::getInstance()->register( RabbitMQPool::class );
	    } catch ( PoolException $e ) {
	    }

	    $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}