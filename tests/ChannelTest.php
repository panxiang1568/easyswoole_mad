<?php

namespace App\model;

use App\entity\AppRequestEntity;
use App\exception\ValidateException;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    use CommonTools;

    protected $redis;
    protected $appRequestEntity;

    public function setUp()
    {

        //生成Entity
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
        //获取redis
        $this->redis = self::getRedis();

    }


    public function testGet()
    {

        $this->expectException(ValidateException::class);

        //测试1 -> 如果渠道未注册或者渠道状态为审核未通过，则抛出异常
        Channel::get($this->appRequestEntity);
        //测试2 -> 配置缓存渠道ID 5，校验渠道ID=5
        $this->redis->hset("appkey:6ruzzdkgm3vrl6admdvqxk1t", "wingtechA4s",  5);
        $this->assertEquals(5, Channel::get($this->appRequestEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
        $this->redis->del("appkey:6ruzzdkgm3vrl6admdvqxk1t");

    }
}
