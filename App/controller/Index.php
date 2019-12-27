<?php

namespace App\controller;

use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\model\CommonTools;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Stream;
use EasySwoole\Http\Request;
use ryan\cache\RedisObject;
use ryan\cache\RedisPool;
use Swoole\Coroutine as Co;

/**
 * 仅用于测试
 * Class Index
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:11
 * @package App\controller
 */
class Index extends Controller
{
	use CommonTools;

	public function index()
	{
//		self::ampqSend();
		self::ampqReceive();
//		var_dump(self::getRabbitMq());
	}

	public function receive()
	{
		self::ampqReceive();
	}

	public function send()
	{
		$time = microtime(true);
//		go(function (){
			for ($i=0;$i<1000;$i++) {

				self::ampqSend();
//				self::ampqSend2();
			}
//		});
		$time2 = microtime(true);
		echo $time2 - $time;
	}
}