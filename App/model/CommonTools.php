<?php
namespace App\model;

use App\utils\Log;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\Config;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPLazyConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use ryan\cache\RedisObject;
use ryan\cache\RedisPool;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use ryan\mq\RabbitMQObject;
use ryan\mq\RabbitMQPool;

/**
 * model通用工具
 * Trait CommonTools
 */
trait CommonTools
{


	public static function getRedis(): RedisObject
	{

		try {
			return RedisPool::defer();
		} catch (PoolEmpty $e) {
		} catch (PoolException $e) {
		}
		return null;
	}

	public static function getRabbitMq(): ?AMQPChannel
	{
		try {
			return RabbitMQPool::defer();
		} catch ( PoolEmpty $e ) {
		} catch ( PoolException $e ) {
		} catch (AMQPTimeoutException $e) {
			echo '111';
		}
		return null;
	}

	public static function ampqSend($content)
	{

		$channel = self::getRabbitMq();

//		$content = '[2019-11-21 14:21:32] {"version":"M4.6","session":"APP-REQ","devid":"IMEI:167695029053468","utdid":"W2j0697hGy0BABeFf4Rby8XO","man":"Coolpad","mod":"CoolpadE580","osv":"6.0","lang":"zh-CN","operator":"unknown","dldir":"sd","avaisize":"7867158528","totalsize":"12149530624","mac":"d0:37:42:56:4d:4d","carrier":{"appid":"lgh2qmtd9soiso01qecfyytv","pkgname":"com.redstone.ota.ui","channel":"test","version":"4.2.53","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.36"},"ip":"0.0.0.0","country":"","region":"","city":"","task_id":["2","1"]}';

		$msg = new AMQPMessage($content);

		$channel->basic_publish($msg, '', 'statistic_pull_log');

//		$channel->close();
//		$connection->close();
	}


	public static function ampqReceive()
	{
		$host = Config::getInstance()->getConf('RabbitMQ.host');
		$port = Config::getInstance()->getConf('RabbitMQ.port');
		$username = Config::getInstance()->getConf('RabbitMQ.username');
		$password = Config::getInstance()->getConf('RabbitMQ.password');

		$connection = new AMQPStreamConnection($host, $port, $username, $password);
		$channel = $connection->channel();
		$channel->queue_declare('statistic_pull_log', false,
			false, false, false);

		echo " [*] Waiting for message. To exit press CTRL+C".PHP_EOL;

		$callback = function ($msg) {
//			var_dump($msg->body);
			Log::write('./Log/task_request.log', $msg->body.PHP_EOL);
		};

		$channel->basic_consume('statistic_pull_log', '', false,
			true, false, false, $callback);

		while ($channel->is_consuming()) {
			$channel->wait();
		}
		$channel->close();
		$connection->close();
	}

}