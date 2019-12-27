<?php

namespace App\entity;


use App\constant\C;
use App\exception\DataException;
use ryan\tools\Http;

/**
 * 任务检测请求实体类
 * Class AppRequestEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:12
 * @package App\entity
 */
class EventAndStatusRequestEntity extends BaseEntity
{
	/**
	 * 请求信息
	 */
	private $version;
	private $session;

	private $ip;

	//状态上报log所需信息
	private $log_data;

	/**
	 * 请求实例构造函数
	 * 实例化时反序列化数据，并验证数据完整性
	 * AppRequestEntity constructor.
	 *
	 * @param $serialized
	 * @param $header
	 */
	public function __construct($serialized, $header)
	{
		$this->setIp(Http::ip($header));
		$this->unserialize($serialized);
		$this->isEmpty();
	}

	/**
	 * 验证必要字段
	 * 前期规则简单，如规则复杂创建独立validate类
	 * @return bool true 不为空
	 */
	public function isEmpty()
	{
		if (empty($this->version)) {
			throw new DataException('invalid version');
		}
		if (empty($this->session)) {
			throw new DataException('invalid session');
		}

		return true;
	}


	/**
	 * 序列化
	 * @return string
	 */
	public function serialize(): string
	{
		$data = [];
		$data[C::$KEY_VERSION] = $this->getVersion();
		$data[C::$KEY_SESSION] = $this->getSession();

		return parent::encode($data);
	}


	/**
	 * 反序列化
	 * @param $serialized
	 */
	public function unserialize($serialized): void
	{
		$body = parent::decode($serialized);

		$this->setVersion($body[C::$KEY_VERSION] ?? "");
		$this->setSession($body[C::$KEY_SESSION] ?? "");

		//如果请求是状态上报则设置log所需数据
		if ($this->getSession() == C::$SESSION_NOTIFY_APP_RSP) {

			$this->setLogData($body);
		}

	}

	/**
	 * @return mixed
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * @param mixed $version
	 */
	public function setVersion(?string $version): void
	{
		$this->version = $version;
	}

	/**
	 * @return mixed
	 */
	public function getSession(): string
	{
		return $this->session;
	}

	/**
	 * @param mixed $session
	 */
	public function setSession(?string $session): void
	{
		if ($session == C::$SESSION_NOTIFY_APP_REQ) {
			$this->session = C::$SESSION_NOTIFY_APP_RSP;
		} elseif ($session == C::$SESSION_EVENT_REQ) {
			$this->session = C::$SESSION_EVENT_RSP;
		}

	}
	/**
	 * 设置log数据
	 * @param array $log_data
	 */
	private function setLogData(array $log_data)
	{
		$data['ip'] = $this->getIp();
		$this->log_data = json_encode(array_merge($log_data['status'], $data));
    }

	/**
	 * 获取log数据
	 * @return mixed
	 */
    public function getLogData()
    {
    	return $this->log_data;
    }

	/**
	 * @return mixed
	 */
	public function getIp(): string
	{
		return $this->ip;
	}

	/**
	 * @param mixed $ip
	 */
	public function setIp(?string $ip): void
	{
		$this->ip = $ip;
	}


}