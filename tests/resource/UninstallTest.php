<?php

namespace App\logic\resource;

use App\constant\C;
use PHPUnit\Framework\TestCase;

/**
 * 卸载 单元测试类
 * Class UninstallTest
 * @package App\logic\resource
 */
class UninstallTest extends TestCase
{

	private $response_task = [], $resource_info = [];

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 4;

		//资源信息json
		$resource_json = '{"pkgname": "com.peanut.fake"}';
		$this->resource_info = json_decode($resource_json, true);

	}


	public function testRun()
	{
		//执行卸载类型资源封装
		$result = Uninstall::run($this->response_task, $this->resource_info);

		$this->assertArrayHasKey(C::$KEY_PKGNAME, $result, 'Missing key ' . C::$KEY_PKGNAME);
	}
}
