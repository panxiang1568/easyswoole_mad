<?php

namespace App\logic\resource;


use App\constant\C;
use EasySwoole\EasySwoole\Config;
use PHPUnit\Framework\TestCase;

/**
 * 弹框 单元测试类
 * Class DialogTest
 * @package App\logic\resource
 */
class DialogTest extends TestCase
{
	private $response_task = [], $resource_info_one = [], $resource_info_two = [], $url_domain, $scheme;

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 6;

		//type_action为01的任务resource_info
		$resource_json_one = '{"type_action": "01", "title": "test image title", "icon": "/Uploads/Home/img/2016-08-16/xLzTGT0hgbhqPgiYuB7RuFxj.png", "objecturi": "http://www.baidu.com","brief":""}';
		$this->resource_info_one = json_decode($resource_json_one, true);

		//type_action为02的任务resource_info
		$resource_json_two = '{"type_action":"02","title":"\u5feb\u901f\u6e05\u7406\u624b\u673a\u5783\u573e","icon":"\/Uploads\/Default\/20191011\/TIM\u56fe\u724720190906155805.jpg","start_type":"1","objecturi":"\/Uploads\/mad_new\/b5479dbc3f6cc69a0ee00a24bb551dec.apk","appname":"444","pkgname":"com.redstone.phone.guard","objectsize":"5475020","version":"1.0","version_code":"1","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.redstone.phone.guard.home.MainActivity","extra":[]},"brief":""}';
		$this->resource_info_two = json_decode($resource_json_two, true);

		$this->url_domain = Config::getInstance()->getConf('url_domain');

//		$this->scheme = Http::scheme();
		$this->scheme = 'http';

	}

	public function testRun()
	{
		//执行弹框类型资源封装
		$result_one = Dialog::run($this->response_task, $this->resource_info_one, $this->url_domain, $this->scheme);

		$this->assertArrayHasKey(C::$KEY_BRIEF, $result_one, '01Missing key ' . C::$KEY_BRIEF);
		$this->assertArrayHasKey(C::$KEY_TITLE, $result_one, '01Missing key ' . C::$KEY_TITLE);
		$this->assertArrayHasKey(C::$KEY_APPNAME, $result_one, '01Missing key ' . C::$KEY_APPNAME);
		$this->assertArrayHasKey(C::$KEY_ACTION, $result_one, '01Missing key ' . C::$KEY_ACTION);
		$this->assertArrayHasKey(C::$KEY_ICON, $result_one, '01Missing key ' . C::$KEY_ICON);
		$this->assertArrayHasKey(C::$KEY_OBJECT_URI, $result_one, '01Missing key ' . C::$KEY_OBJECT_URI);

		$result_two = Dialog::run($this->response_task, $this->resource_info_two, $this->url_domain, $this->scheme);

		$this->assertArrayHasKey(C::$KEY_BRIEF, $result_two, '02Missing key ' . C::$KEY_BRIEF);
		$this->assertArrayHasKey(C::$KEY_TITLE, $result_two, '02Missing key ' . C::$KEY_TITLE);
		$this->assertArrayHasKey(C::$KEY_APPNAME, $result_two, '02Missing key ' . C::$KEY_APPNAME);
		$this->assertArrayHasKey(C::$KEY_ACTION, $result_two, '02Missing key ' . C::$KEY_ACTION);
		$this->assertArrayHasKey(C::$KEY_ICON, $result_two, '02Missing key ' . C::$KEY_ICON);
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
