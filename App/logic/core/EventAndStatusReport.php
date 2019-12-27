<?php

namespace App\logic\core;
use App\constant\C;
use App\entity\EventAndStatusRequestEntity;
use App\entity\EventAndStatusResponseEntity;
use App\entity\IEntity;
use App\utils\Log;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

/**
 * 状态上报
 * Class EventReport
 * @package App\logic\core
 */
class EventAndStatusReport implements ITask
{

	private $response;
	private $request;

	//事件报告和状态上报请求实体
	private $eventAndStatusRequestEntity;
	//事件报告和状态上报响应实体
	private $eventAndStatusResponseEntity;

	public function __construct(Request $request, Response $response)
	{
		/**
		 * 设置Swoole Request
		 */
		$this->request = $request;
		/**
		 * 设置Swoole Response
		 */
		$this->response = $response;
	}

	public function init(): void
	{
		/**
		 * 初始化EventAndStatusRequestEntity
		 */
		$this->eventAndStatusRequestEntity = new EventAndStatusRequestEntity($this->request->getBody()->__toString(),
			$this->request->getHeaders());

		/**
		 * 初始化EventRequestEntity Entity
		 */
		$this->eventAndStatusResponseEntity = new EventAndStatusResponseEntity();
		$this->eventAndStatusResponseEntity->setIsBase64($this->eventAndStatusRequestEntity->isBase64());
		$this->eventAndStatusResponseEntity->setIsMapData($this->eventAndStatusRequestEntity->isMapData());
		$this->eventAndStatusResponseEntity->setVersion($this->eventAndStatusRequestEntity->getVersion());
		$this->eventAndStatusResponseEntity->setSession($this->eventAndStatusRequestEntity->getSession());
		$this->eventAndStatusResponseEntity->setCode(C::$CODE_1500);
		$this->eventAndStatusResponseEntity->setMsg(C::$SUCCESS);
	}

	public function main(): string
	{
		//初始化数据
		$this->init();

		//返回序列化结果
		return $this->getResponseEntity()->serialize();
	}

	public function record()
	{
		//如果是状态上报则记录请求日志
		if ($this->getRequestEntity()->getSession() == C::$SESSION_NOTIFY_APP_RSP) {
			Log::write(Config::getInstance()->getConf('status_report').'statis_msg_day_'.date('Ymd').'.log',
				'['.date('Y-m-d H:i:s').'] '.$this->getRequestEntity()->getLogData() . "\n");
		}
	}

	/**
	 * 获取Swoole响应对象
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}

	/**
	 * 获取Swoole请求对象
	 * @return Request
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}

	/**
	 * 获取响应实体对象
	 * @return IEntity
	 */
	public function getResponseEntity(): IEntity
	{
		return $this->eventAndStatusResponseEntity;
	}

	/**
	 * 获取请求实体对象
	 * @return EventAndStatusRequestEntity
	 */
	public function getRequestEntity(): EventAndStatusRequestEntity
	{
		return $this->eventAndStatusRequestEntity;
	}
}