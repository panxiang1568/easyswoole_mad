<?php

namespace App\logic\resource;

use App\constant\C;
use PHPUnit\Framework\TestCase;

/**
 * 启动 单元测试类
 * Class LaunchTest
 * @package App\logic\resource
 */
class LaunchTest extends TestCase
{
	private $response_task = [], $resource_info = [];

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 3;

		//资源信息json
		$resource_json = '{"pkgname": "com.redstone.ota.ui", "start": {"class": "com.redstone.ota.ui.activity.RsMainActivity", "type": "activity", "action": "android.intent.action.MAIN", "extra": []}}';
		$this->resource_info = json_decode($resource_json, true);

	}


	public function testRun()
	{
		//执行启动类型资源封装
		$result = Launch::run($this->response_task, $this->resource_info);

		$this->assertArrayHasKey(C::$KEY_PKGNAME, $result, 'Missing key ' . C::$KEY_PKGNAME);
		$this->assertArrayHasKey(C::$KEY_START, $result, 'Missing key ' . C::$KEY_START);
		$this->assertEquals('1.0', $result[C::$KEY_VERSION], C::$KEY_VERSION . ' value incorrect');
		$this->assertEquals('1', $result[C::$KEY_VERSION_CODE], C::$KEY_VERSION_CODE);
	}
}
