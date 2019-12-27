<?php

namespace App\logic\validate\task;

use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class DeviceValidateTest extends TestCase
{
    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3],设置设备所属分组为''，设置设备为自定义设备，所属任务ID集合为''
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setTaskIds([3]);
        $this->appRequestEntity->setGroupIds('');
        $this->appRequestEntity->setTaskDeviceIds('');

        //生成TaskEntity ,设置任务ID=3，设置任务的高级配置信息''
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);
        $this->taskEntity->setAdvanceConfig('');

    }

    public function testRun()
    {

        //1、任务既无分组也无自定义设备，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //2、任务分组配置为'1,2,3'，任务无自定义设备，设备不在任务分组内，校验失败
        $this->assertEquals(false, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //设置设备所属分组为'1,5'
        $this->appRequestEntity->setGroupIds('1,5');
        //3、任务分组配置为'1,2,3'，任务无自定义设备，设备在分组'1，5'内，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'5'
        $this->appRequestEntity->setGroupIds('5');
        //4、任务分组配置为'1,2,3'，任务有自定义设备，设备不在任务分组内也不属于该任务的自定义设备，校验失败
        $this->assertEquals(false, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'2'
        $this->appRequestEntity->setGroupIds('2');
        //5、任务分组配置为'1,2,3'，任务有自定义设备，设备在任务分组内，不属于该任务的自定义设备，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'5'
        $this->appRequestEntity->setGroupIds('5');
        //设备为任务自定义设备，设置设备所属任务ID集合'3,4'
        $this->appRequestEntity->setTaskDeviceIds('3,4');
        //6、任务分组配置为'1,2,3'，任务有自定义设备，设备不在任务分组内，属于该任务的自定义设备，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置'1,2,3'
        $this->taskEntity->setGroup('1,2,3');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'2'
        $this->appRequestEntity->setGroupIds('2');
        //设备为任务自定义设备，设置设备所属任务ID集合'3,4'
        $this->appRequestEntity->setTaskDeviceIds('3,4');
        //7、任务分组配置为'1,2,3'，任务有自定义设备，设备在任务分组内，属于该任务的自定义设备，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置''
        $this->taskEntity->setGroup('');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'2'
        $this->appRequestEntity->setGroupIds('2');
        //设备为任务自定义设备，设置设备所属任务ID集合'3,4'
        $this->appRequestEntity->setTaskDeviceIds('3,4');
        //8、任务分组配置为''，任务有自定义设备，设备不在任务分组内，属于该任务的自定义设备，校验成功
        $this->assertEquals(true, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的分组配置''
        $this->taskEntity->setGroup('');
        //设置任务存在自定义设备
        $this->taskEntity->setCustomDevice(1);
        //设置设备所属分组为'2'
        $this->appRequestEntity->setGroupIds('2');
        //设备为任务自定义设备，设置设备所属任务ID集合'4'
        $this->appRequestEntity->setTaskDeviceIds('4');
        //9、任务分组配置为''，任务有自定义设备，设备不在任务分组内，不属于该任务的自定义设备，校验失败
        $this->assertEquals(false, DeviceValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
