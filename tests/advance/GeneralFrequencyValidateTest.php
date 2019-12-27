<?php

namespace App\logic\filter;


use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\logic\validate\task\GeneralFrequencyValidate;
use App\model\CommonTools;
use PHPUnit\Framework\TestCase;

class GeneralFrequencyValidateTest extends TestCase
{
    use CommonTools;
    protected $redis;
    protected $appRequestEntity;
    protected $task_id;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(2);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3
        $this->taskEntity = new TaskEntity();

    }

    public function testRun()
    {

        //设置任务类型为普通类型
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_GENERAL);
        //1、任务类型是普通任务,返回任务集中不存在其他普通任务，校验成功
        $this->assertEquals(true, GeneralFrequencyValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务集中存在其他可下发的普通任务
        $this->appRequestEntity->setIsContainGeneral(true);
        //2、任务类型是普通任务,返回任务集中存在其他普通任务，校验失败
        $this->assertEquals(false, GeneralFrequencyValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务类型为多次激活任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置任务集中存在其他可下发的普通任务
        $this->appRequestEntity->setIsContainGeneral(true);
        //设置任务类型为多次激活任务，返回任务集中存在其他普通任务，校验成功
        $this->assertEquals(true, GeneralFrequencyValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();

    }

}
