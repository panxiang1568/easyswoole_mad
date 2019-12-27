<?php

namespace App\model;


use App\entity\AppRequestEntity;
use PHPUnit\Framework\TestCase;

class IPTest extends TestCase
{

	private $appRequestEntity;

	public function setUp() {
		//生成Entity
		$this->appRequestEntity = new AppRequestEntity('{"debug_ip": "219.133.168.5","version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');

	}

	public function testGet()
	{
		$res = IP::parse($this->appRequestEntity->getIp());
		$this->assertIsArray($res);
		$this->assertEquals(4, count($res));
	}


}
