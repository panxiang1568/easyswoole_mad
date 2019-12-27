<?php

namespace App\model;

use App\entity\AppRequestEntity;
use App\exception\ValidateException;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * 获取设备信息 测试用例
 * Class DeviceTest
 * @package App\model
 */
class DeviceTest extends TestCase {
	use CommonTools;

	private $redis, $appRequestEntity;

	public function setUp() {
		$this->appRequestEntity = new AppRequestEntity( '{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}' );
		$this->appRequestEntity->setChannelId( 22 );
		$this->redis = self::getRedis();
	}

	/**
	 * 测试 获取设备信息方法
	 * @throws Throwable
	 */
	public function testGet()
	{
		$this->expectException( ValidateException::class );
		Device::get( $this->appRequestEntity );

		$this->redis->hSet( 'device:' . $this->appRequestEntity->getChannelId(),
			$this->appRequestEntity->getDevid(), '5|1562570025' );

		$device_info = Device::get( $this->appRequestEntity );
		$this->assertEquals( 5, $device_info[0] );
		$this->assertEquals( 1562570025, $device_info[1] );


	}

	/**
	 * 测试 获取设备测试状态方法
	 * @throws Throwable
	 */
	public function testIsTest() {
		$this->redis->sAdd( 'testdev:' . $this->appRequestEntity->getChannelId(),
			$this->appRequestEntity->getDevid() );

		$this->assertEquals( true, Device::isTest( $this->appRequestEntity ) );
		$this->assertIsBool( Device::isTest( $this->appRequestEntity ) );

	}

	public function tearDown() {
		$this->redis->delete('device:' . $this->appRequestEntity->getChannelId());
		parent::tearDown();

	}
}
