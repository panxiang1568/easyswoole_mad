<?php

namespace App\entity;

use App\constant\C;

/**
 * 事件报告响应实体类
 * Class EventResponseEntity
 * @package App\entity
 */
class EventAndStatusResponseEntity extends BaseEntity implements IEntity
{
	/**
	 * 响应字段
	 */
	private $version;
	private $session;
	private $code;

	/**
	 * 序列化
	 * @return string
	 */
	public function serialize(): string
	{
		$response = [
			C::$KEY_VERSION => $this->getVersion(),
			C::$KEY_CODE => $this->getCode(),
		];

		$this->getSession() && $response[C::$KEY_SESSION] = $this->getSession();
		$this->getMsg() && $response[C::$KEY_MSG] = $this->getMsg();

		return parent::encode($response);
	}

	/**
	 * 反序列化
	 * @param $serialized
	 */
	public function unserialize($serialized): void
	{
		return;
	}

	/**
	 * @return mixed
	 */
	public function getVersion(): string
	{
		return $this->version ?? "1.0";
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
		return $this->session ?? "";
	}

	/**
	 * @param mixed $session
	 */
	public function setSession(?string $session): void
	{
		$this->session = $session;
	}

	/**
	 * @return mixed
	 */
	public function getCode(): string
	{
		return $this->code ?? '1500';
	}

	/**
	 * @param mixed $code
	 */
	public function setCode(?string $code): void
	{
		$this->code = $code;
	}



}