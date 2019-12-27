<?php

namespace App\logic\resource;

use App\constant\C;
use EasySwoole\EasySwoole\Config;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{

	private $response_task = [], $resource_info = [], $url_domain, $scheme;

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 5;

		//资源信息json
		$resource_json = '{"objecturi":"http:\/\/www.sohu.com"}';
		$this->resource_info = json_decode($resource_json, true);

		$this->url_domain = Config::getInstance()->getConf('url_domain');

//		$this->scheme = Http::scheme();
		$this->scheme = 'http';
	}


	public function testRun()
	{
		//执行链接类型资源封装
		$result = Link::run($this->response_task, $this->resource_info, $this->url_domain, $this->scheme);

		$this->assertArrayHasKey(C::$KEY_OBJECT_URI, $result, 'Missing key ' . C::$KEY_OBJECT_URI);
	}
}
