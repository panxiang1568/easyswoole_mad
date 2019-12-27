<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class UpgradeValidateTest extends TestCase
{

    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setTaskIds([3]);
        $this->appRequestEntity->setImpl('4.0.3');

        //生成TaskEntity ,设置任务ID=3,任务资源类型为自升级
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);
        $this->taskEntity->setResource(C::$RESOURCE_UPGRADE);

    }

    public function testRun()
    {

        //1、未设置任务的impl和stub版本
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.1',stub版本1
        $this->taskEntity->setResourceInfo('{"impl":"4.0.1","stub":1}');
        //2、设备的impl版本'4.0.3',任务的impl版本'4.0.1',校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本1
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":1}');
        //设置设备的stub版本'4.0.11'
        $this->appRequestEntity->setStubVersion('4.0.11');
        //3、设备的impl版本'4.0.3',设备的stub版本'4.0.11',任务的impl版本'4.0.4',任务的stub版本为1，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本1
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":1}');
        //设置设备的stub版本'4.0.9'
        $this->appRequestEntity->setStubVersion('4.0.9');
        //4、设备的impl版本'4.0.3',设备的stub版本'4.0.9',任务的impl版本'4.0.4',任务的stub版本为1，校验成功
        $this->assertEquals(true, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本2
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":2}');
        //设置设备的stub版本'4.0.11'
        $this->appRequestEntity->setStubVersion('4.0.11');
        //5、设备的impl版本'4.0.3',设备的stub版本'4.0.11',任务的impl版本'4.0.4',任务的stub版本为2，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本2
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":2}');
        //设置设备的stub版本'4.0.9'
        $this->appRequestEntity->setStubVersion('4.0.9');
        //6、设备的impl版本'4.0.3',设备的stub版本'4.0.9',任务的impl版本'4.0.4',任务的stub版本为2，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本2
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":2}');
        //设置设备的stub版本'4.0.12'
        $this->appRequestEntity->setStubVersion('4.0.12');
        //7、设备的impl版本'4.0.3',设备的stub版本'4.0.12',任务的impl版本'4.0.4',任务的stub版本为2，校验成功
        $this->assertEquals(true, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本3
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":3}');
        //设置设备的stub版本'4.0.15'
        $this->appRequestEntity->setStubVersion('4.0.15');
        //8、设备的impl版本'4.0.3',设备的stub版本'4.0.15',任务的impl版本'4.0.4',任务的stub版本为3，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本3
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":3}');
        //设置设备的stub版本'4.0.9'
        $this->appRequestEntity->setStubVersion('4.0.9');
        //9、设备的impl版本'4.0.3',设备的stub版本'4.0.9',任务的impl版本'4.0.4',任务的stub版本为3，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本3
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":3}');
        //设置设备的stub版本'4.0.14'
        $this->appRequestEntity->setStubVersion('4.0.14');
        //10、设备的impl版本'4.0.3',设备的stub版本'4.0.14',任务的impl版本'4.0.4',任务的stub版本为3，校验成功
        $this->assertEquals(true, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本4
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":4}');
        //设置设备的stub版本'4.0.9'
        $this->appRequestEntity->setStubVersion('4.0.9');
        //11、设备的impl版本'4.0.3',设备的stub版本'4.0.9',任务的impl版本'4.0.4',任务的stub版本为4，校验失败
        $this->assertEquals(false, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的资源信息：impl版本'4.0.4',stub版本4
        $this->taskEntity->setResourceInfo('{"impl":"4.0.4","stub":4}');
        //设置设备的stub版本'4.2.31'
        $this->appRequestEntity->setStubVersion('4.2.31');
        //12、设备的impl版本'4.0.3',设备的stub版本'4.2.31',任务的impl版本'4.0.4',任务的stub版本为4，校验成功
        $this->assertEquals(true, UpgradeValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();

    }
}
