<?php

namespace App\logic\resource;

use App\constant\C;
use EasySwoole\EasySwoole\Config;
use PHPUnit\Framework\TestCase;

/**
 * 状态栏 单元测试类
 * Class NotificationTest
 * @package App\logic\resource
 */
class NotificationTest extends TestCase
{
	private $response_task = [], $resource_info_one = [], $resource_info_two = [], $url_domain, $scheme;

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 7;

		//资源信息json
		$resource_json_one = '{"type_action": "01", "img_type": 2, "objecturi": "http://www.baidu.com", "title": "title", "brief": null, "status_icon": "/Uploads/Default/20190826/3a77554d70f6700a62d9f7b906cf688f.png"}';
		$this->resource_info_one = json_decode($resource_json_one, true);

		$resource_json_two = '{"type_action":"02","img_type":"1","status_pic":"\/Uploads\/Default\/20191011\/\u5fae\u4fe1\u56fe\u7247_20191011111257.jpg","start_type":"1","objecturi":"\/Uploads\/mad_new\/f8138ed49393093f575704533665a0cb.apk","icon":"undefined","appname":"666","pkgname":"com.redstone.phone.guard","objectsize":"5475020","version":"1.0","version_code":"1","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.redstone.phone.guard.home.MainActivity","extra":[]}}';
		$this->resource_info_two = json_decode($resource_json_two, true);

		$this->url_domain = Config::getInstance()->getConf('url_domain');

//		$this->scheme = Http::scheme();
		$this->scheme = 'http';
	}

	public function testRun()
	{
		//执行通知栏类型资源封装
		$result_one = Notification::run($this->response_task, $this->resource_info_one, $this->url_domain, $this->scheme);

		if (isset($this->resource_info_one[C::$RSC_KEY_STATUS_ICON])) {
			$this->assertArrayHasKey(C::$KEY_ICON, $result_one, '01Missing key ' . C::$KEY_ICON);
		}

		if (isset($this->resource_info_one[C::$RSC_KEY_STATUS_PIC])) {
			$this->assertArrayHasKey(C::$KEY_PIC, $result_one, '01Missing key ' . C::$KEY_PIC);
		}

		$this->assertArrayHasKey(C::$KEY_BRIEF, $result_one, '01Missing key ' . C::$KEY_BRIEF);
		$this->assertArrayHasKey(C::$KEY_TITLE, $result_one, '01Missing key ' . C::$KEY_TITLE);
		$this->assertArrayHasKey(C::$KEY_APPNAME, $result_one, '01Missing key ' . C::$KEY_APPNAME);
		$this->assertArrayHasKey(C::$KEY_ACTION, $result_one, '01Missing key ' . C::$KEY_ACTION);
		$this->assertArrayHasKey(C::$KEY_OBJECT_URI, $result_one, '01Missing key ' . C::$KEY_OBJECT_URI);

		$result_two = Notification::run($this->response_task, $this->resource_info_two, $this->url_domain, $this->scheme);

		if (isset($this->resource_info_two[C::$RSC_KEY_STATUS_ICON])) {
			$this->assertArrayHasKey(C::$KEY_ICON, $result_two, '02Missing key ' . C::$KEY_ICON);
		}

		if (isset($this->resource_info_two[C::$RSC_KEY_STATUS_PIC])) {
			$this->assertArrayHasKey(C::$KEY_PIC, $result_two, '02Missing key ' . C::$KEY_PIC);
		}

		$this->assertArrayHasKey(C::$KEY_BRIEF, $result_two, '02Missing key ' . C::$KEY_BRIEF);
		$this->assertArrayHasKey(C::$KEY_TITLE, $result_two, '02Missing key ' . C::$KEY_TITLE);
		$this->assertArrayHasKey(C::$KEY_APPNAME, $result_two, '02Missing key ' . C::$KEY_APPNAME);
		$this->assertArrayHasKey(C::$KEY_ACTION, $result_two, '02Missing key ' . C::$KEY_ACTION);
		$this->assertArrayHasKey(C::$KEY_OBJECT_SIZE, $result_two, '02Missing key ' . C::$KEY_OBJECT_SIZE);
		$this->assertArrayHasKey(C::$KEY_VERSION, $result_two, '02Missing key ' . C::$KEY_VERSION);
		$this->assertArrayHasKey(C::$KEY_VERSION_CODE, $result_two, '02Missing key ' . C::$KEY_VERSION_CODE);
		$this->assertArrayHasKey(C::$KEY_PKGNAME, $result_two, '02Missing key ' . C::$KEY_PKGNAME);
		$this->assertArrayHasKey(C::$KEY_OBJECT_URI, $result_two, '02Missing key ' . C::$KEY_OBJECT_URI);

		if ($this->resource_info_two[C::$RSC_KEY_START_TYPE] == 1) {
			$this->assertArrayHasKey(C::$KEY_START, $result_two, '02Missing key ' . C::$KEY_START);
		}

	}
}
