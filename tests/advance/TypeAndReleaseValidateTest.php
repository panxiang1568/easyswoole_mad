<?php

namespace App\logic\validate\task;


use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\model\CommonTools;
use PHPUnit\Framework\TestCase;

class TypeAndReleaseValidateTest extends TestCase
{
    use CommonTools;
    protected $appRequestEntity;
    protected $taskEntity;
    protected $redis;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(2);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3，任务发布量为10，触达量为1
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);
        $this->taskEntity->setMaxNum(10);
        $this->taskEntity->setCurNum(1);

        //redis
        $this->redis = self::getRedis();

    }

    public function testRun()
    {

        //1、设备未拉取过任务3，触达量<发布量，校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为10
        $this->taskEntity->setCurNum(10);
        //2、设备未拉取过任务3，触达量>=发布量，校验失败
        $this->assertEquals(false, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为普通任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_GENERAL);
        //设置设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), '1571278547|0');
        //3、设备拉取过任务3，触达量<发布量，任务类型为普通任务，校验失败
        $this->assertEquals(false, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为无限拉取任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_FOREVER);
        //设置设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), '1571278547|0');
        //4、设备拉取过任务3，触达量<发布量，任务类型为无限拉取，校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为10
        $this->taskEntity->setCurNum(10);
        //设置任务类型为无限拉取任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_FOREVER);
        //设置设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), '1571278547|0');
        //5、设备拉取过任务3，触达量=发布量，任务类型为无限拉取，校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为多次激活
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置多次激活的间隔时间'1,3,7'
        $this->taskEntity->setInterval('1,3,7');
        //设置一天前该设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)).'|0');
        //6、设备1天前第一次拉取过任务3，任务类型为多次激活任务，设置多次激活频次'1,3,7',校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为多次激活
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置多次激活的间隔时间'1,3,7'
        $this->taskEntity->setInterval('1,3,7');
        //设置2天前该设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24*2)).'|0');
        //7、设备2天前第一次拉取过任务3，任务类型为多次激活任务，设置多次激活频次'1,3,7',校验失败
        $this->assertEquals(false, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为多次激活
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置多次激活的间隔时间'1,3,7'
        $this->taskEntity->setInterval('1,3,7');
        //设置3天前该设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24*3)).'|0');
        //8、设备3天前第一次拉取过任务3，任务类型为多次激活任务，设置多次激活频次'1,3,7',校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为多次激活
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置多次激活的间隔时间'1,3,7'
        $this->taskEntity->setInterval('1,3,7');
        //设置一天前该设备拉取过该任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)).'|1');
        //9、设备1天前第一次拉取过任务3，今天第二次拉取了该任务，任务类型为多次激活任务，设置多次激活频次'1,3,7',校验失败
        $this->assertEquals(false, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为间隔拉取
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_INTERVAL);
        //设置间隔拉取时间为3
        $this->taskEntity->setInterval(3);
        //设置2天前拉取过这个任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24*2)).'|0');
        //10、设备2天前拉取过任务3，触达量=发布量，任务类型为间隔拉取任务，设置间隔拉取时间为3,校验失败
        $this->assertEquals(false, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为间隔拉取
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_INTERVAL);
        //设置间隔拉取时间为3
        $this->taskEntity->setInterval(3);
        //设置3天前拉取过这个任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24*3)).'|0');
        //11、设备3天前拉取过任务3，触达量=发布量，任务类型为间隔拉取任务，设置间隔拉取时间为3,校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务触达量为1
        $this->taskEntity->setCurNum(1);
        //设置任务类型为间隔拉取
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_INTERVAL);
        //设置间隔拉取时间为3
        $this->taskEntity->setInterval(3);
        //设置4天前拉取过这个任务
        $this->redis->hset('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId(), strtotime(date('Y-m-d'.'00:00:00',time()-3600*24*4)).'|0');
        //12、设备4天前拉取过任务3，触达量=发布量，任务类型为间隔拉取任务，设置间隔拉取时间为3,校验成功
        $this->assertEquals(true, TypeAndReleaseValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
        //清除缓存
        $this->redis->del('task_pull_history:'.$this->taskEntity->getId());

    }
}
