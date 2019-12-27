<?php

namespace App\logic\validate\task;


use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class AreaValidateTest extends TestCase
{

    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2, 处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(2);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);

    }

    public function testRun()
    {

        //设置设备所属国家为中国
        $this->appRequestEntity->setCountry(C::$CHINA_CODE);
        //设置任务地域为海外
        $this->taskEntity->setArea(C::$TASK_AREA_OVERSEA);
        //1、设备所属国家:中国，任务地域:海外。地区校验失败
        $this->assertEquals(false, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备所属国家为中国
        $this->appRequestEntity->setCountry(C::$CHINA_CODE);
        //设置任务地域为国内
        $this->taskEntity->setArea(C::$TASK_AREA_DOMESTIC);
        //2、设备所属国家:中国，任务地域:国内。地区校验成功
        $this->assertEquals(true, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备所属国家为中国
        $this->appRequestEntity->setCountry(C::$CHINA_CODE);
        //设置任务地域为全球
        $this->taskEntity->setArea(C::$TASK_AREA_GLOBAL);
        //3、设备所属国家:中国，任务地域:全球。地区校验成功
        $this->assertEquals(true, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备所属国家为日本
        $this->appRequestEntity->setCountry('JP');
        //设置任务地域为海外
        $this->taskEntity->setArea(C::$TASK_AREA_OVERSEA);
        //4、设备所属国家:日本，任务地域:海外。地区校验成功
        $this->assertEquals(true, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备所属国家为日本
        $this->appRequestEntity->setCountry('JP');
        //设置任务地域为国内
        $this->taskEntity->setArea(C::$TASK_AREA_DOMESTIC);
        //5、设备所属国家:日本，任务地域:国内。地区校验失败
        $this->assertEquals(false, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备所属国家为日本
        $this->appRequestEntity->setCountry('JP');
        //设置任务地域为全球
        $this->taskEntity->setArea(C::$TASK_AREA_GLOBAL);
        //6、设备所属国家:日本，任务地域:全球。地区校验成功
        $this->assertEquals(true, AreaValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
    }

}
