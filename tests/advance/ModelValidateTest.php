<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class ModelValidateTest extends TestCase
{
    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3，任务机型校验规则为白名单，任务机型配置为'P100,M760'
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);
        //设置任务的高级配置信息
        $this->taskEntity->setAdvanceConfig('');
        //设置任务的机型校验规则为白名单
        $this->taskEntity->setModelRule(C::$TASK_WHITE);
        //设置任务的机型配置
        $this->taskEntity->setModel('P100,M760');

    }

    public function testRun()
    {

        //设置设备的机型为'M760'
        $this->appRequestEntity->setMod('M760');
        //1、任务的机型校验规则为白名单，任务的机型配置：'P100,M760', 设备机型为'M760'，校验成功
        $this->assertEquals(true, ModelValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备的机型为'M761'
        $this->appRequestEntity->setMod('M761');
        //2、任务的机型校验规则为白名单，任务的机型配置：'P100,M760', 设备机型为'M761'，校验失败
        $this->assertEquals(false, ModelValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的机型校验规则为黑名单
        $this->taskEntity->setModelRule(C::$TASK_BLACK);

        //设置设备的机型为'M760'
        $this->appRequestEntity->setMod('M760');
        //1、任务的机型校验规则为黑名单，任务的机型配置：'P100,M760', 设备机型为'M760'，校验失败
        $this->assertEquals(false, ModelValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备的机型为'M761'
        $this->appRequestEntity->setMod('M761');
        //2、任务的机型校验规则为黑名单，任务的机型配置：'P100,M760', 设备机型为'M761'，校验成功
        $this->assertEquals(true, ModelValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
