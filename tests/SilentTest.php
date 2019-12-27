<?php

namespace App\model;


use App\entity\AppRequestEntity;
use PHPUnit\Framework\TestCase;

class SilentTest extends TestCase
{

    use CommonTools;
    protected $redis;
    protected $appRequestEntity;

    public function setUp()
    {

        //生成Entity
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
        $this->appRequestEntity->setMod("CMCC");

        //获取redis 【Silent】
        $this->redis = self::getRedis();

    }

    public function testGet()
    {
        //测试1 -> 校验默认周期 = 90
        $this->assertEquals(90, Silent::get($this->appRequestEntity));

        //测试2 -> 配置产品的静默期 6，校验产品的周期 = 6
        $this->redis->hset("silent", "6ruzzdkgm3vrl6admdvqxk1t",  6);
        $this->assertEquals(6, Silent::get($this->appRequestEntity));

        //测试3 -> 配置渠道的静默期4，校验渠道的周期 = 4
        $this->redis->hset("silent", "6ruzzdkgm3vrl6admdvqxk1t_wingtechA4s",  4);
        $this->assertEquals(4, Silent::get($this->appRequestEntity));

        //测试4 -> 配置机型的静默期2，校验机型的周期 = 2
        $this->redis->hset("silent", "6ruzzdkgm3vrl6admdvqxk1t_wingtechA4s_CMCC",  2);
        $this->assertEquals(2, Silent::get($this->appRequestEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
        //清除缓存
        $this->redis->hdel("silent","6ruzzdkgm3vrl6admdvqxk1t");
        $this->redis->hdel("silent","6ruzzdkgm3vrl6admdvqxk1t_wingtechA4s");
        $this->redis->hdel("silent","6ruzzdkgm3vrl6admdvqxk1t_wingtechA4s_CMCC");

    }
}
