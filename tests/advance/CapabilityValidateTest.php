<?php

namespace App\logic\validate\task;


use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class CapabilityValidateTest extends TestCase
{

    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(2);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);

    }

    public function testRun()
    {
        //设置任务类型为2(下载安装并启动)
        $this->taskEntity->setResource(C::$RESOURCE_INSTALL_AND_LAUNCH);
        //1、任务类型为2时，设备默认能力['01', '02', '03']，能力校验成功
        $this->assertEquals(true, CapabilityValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务类型为4(卸载)
        $this->taskEntity->setResource(C::$RESOURCE_UNINSTALL);
        //2、任务类型为4时，设备默认能力['01', '02', '03']，能力校验失败
        $this->assertEquals(false, CapabilityValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备能力01|03|05|07
        $this->appRequestEntity->setCapability('01|03|05|07');
        //设置任务类型为5(打开链接)
        $this->taskEntity->setResource(C::$RESOURCE_LINK);
        //3、任务类型为5，设备能力01|03|05|07，能力校验成功
        $this->assertEquals(true, CapabilityValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备能力01|03|05|07
        $this->appRequestEntity->setCapability('01|03|05|07');
        //设置任务类型为6(弹框)
        $this->taskEntity->setResource(C::$RESOURCE_DIALOG);
        //3、任务类型为6，设备能力01|03|05|07，能力校验失败
        $this->assertEquals(false, CapabilityValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();

    }
}
