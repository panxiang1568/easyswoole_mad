<?php

namespace App\logic\resource;

use App\constant\C;
use EasySwoole\EasySwoole\Config;
use PHPUnit\Framework\TestCase;

/**
 * 下载安装并启动 单元测试类
 * Class InstallAndLaunchTest
 * @package App\logic\resource
 */
class InstallAndLaunchTest extends TestCase
{

	private $response_task = [], $resource_info = [], $url_domain, $scheme;

	public function setUp()
	{
		//任务ID，设为1
		$this->response_task[C::$KEY_TASK_ID] = 1;
		//设备号
		$this->response_task[C::$KEY_CORRELATOR] = 'IMEI:GBS77089SG8889';
		//资源类型
		$this->response_task[C::$KEY_OPERATION]  = 2;

		//资源信息json
		$resource_json = '{"wifionly":"1","objecturi":"\/Uploads\/mad_new\/df7ca067e1323a5f00f1a0cd3da549b1.apk","icon":"undefined","appname":"mgc","pkgname":"com.rumedia.videoplayer","objectsize":"8552534","version":"2.0.2","version_code":"22","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.mgc.letobox.happy.SplashActivity","extra":[]}}';
		$this->resource_info = json_decode($resource_json, true);

		$this->url_domain = Config::getInstance()->getConf('url_domain');

//		$this->scheme = Http::scheme();
		$this->scheme = 'http';

	}


	public function testRun()
	{
		//执行下载安装类型资源封装
		$result = InstallAndLaunch::run($this->response_task, $this->resource_info, $this->url_domain, $this->scheme);

		$this->assertArrayHasKey(C::$KEY_BRIEF, $result, 'Missing key ' . C::$KEY_BRIEF);
		$this->assertArrayHasKey(C::$KEY_APPNAME, $result, 'Missing key ' . C::$KEY_APPNAME);
		$this->assertArrayHasKey(C::$KEY_ICON, $result, 'Missing key ' . C::$KEY_ICON);
		$this->assertArrayHasKey(C::$KEY_OBJECT_SIZE, $result, 'Missing key ' . C::$KEY_OBJECT_SIZE);
		$this->assertArrayHasKey(C::$KEY_VERSION, $result, 'Missing key ' . C::$KEY_VERSION);
		$this->assertArrayHasKey(C::$KEY_VERSION_CODE, $result, 'Missing key ' . C::$KEY_VERSION_CODE);
		$this->assertArrayHasKey(C::$KEY_PKGNAME, $result, 'Missing key ' . C::$KEY_PKGNAME);
		$this->assertArrayHasKey(C::$KEY_OBJECT_URI, $result, 'Missing key ' . C::$KEY_OBJECT_URI);
		$this->assertArrayHasKey(C::$KEY_WIFI_ONLY, $result, 'Missing key ' . C::$KEY_WIFI_ONLY);
		$this->assertArrayHasKey(C::$KEY_START, $result, 'Missing key ' . C::$KEY_START);
	}
}
