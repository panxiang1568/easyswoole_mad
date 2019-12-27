<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use App\exception\UpgradeException;
use App\model\CommonTools;
use PHPUnit\Framework\TestCase;

class TouchValidateTest extends TestCase
{
    use CommonTools;
    protected $appRequestEntity;
    protected $taskEntity;
    protected $redis;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3],设置设备所属分组为''
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);

        //redis
        $this->redis = self::getRedis();

    }

    public function testRun()
    {

        //设置设备未拉取过该任务
        $this->taskEntity->setIsPull(false);

        //设置设备触达量为10
        $this->taskEntity->setCurNum(10);
        //设置设备发布量为10
        $this->taskEntity->setMaxNum(10);
        //1、设置设备未拉取过该任务，设置任务的触达量10，发布量10，校验失败
        $this->assertEquals(false, TouchValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备触达量为1
        $this->taskEntity->setCurNum(1);
        //设置设备发布量为10
        $this->taskEntity->setMaxNum(10);
        //2、设置设备未拉取过该任务，设置任务的触达量1，发布量10，校验成功
        $this->assertEquals(true, TouchValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备拉取过该任务
        $this->taskEntity->setIsPull(true);
        //设置任务类型为多次激活
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_MULTI);
        //设置设备第一次拉取该任务的时间为昨天
        $this->taskEntity->setTaskPullTime(strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)));
        //3、设置任务被拉取过，设置任务类型为多次激活，设置设备第一次拉取该任务的时间为昨天，拉取数量为0，校验成功
        $this->assertEquals(true, TouchValidate::run($this->appRequestEntity, $this->taskEntity));
        //4、验证设备第一次拉取该任务的时间为昨天，拉取数量为1
        $this->assertEquals(strtotime(date('Y-m-d'.'00:00:00',time()-3600*24)).'|1', $this->redis->hget('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId()));

        //设置设备拉取过该任务
        $this->taskEntity->setIsPull(true);
        //设置任务类型为普通任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_GENERAL);
        //5、设置任务类型为普通任务，校验成功
        $this->assertEquals(true, TouchValidate::run($this->appRequestEntity, $this->taskEntity));
        //6、校验设备最后一次拉取该任务的时间为今天，拉取数量为0
        $last_pull = explode('|', $this->redis->hget('task_pull_history:'.$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId()));
        $this->assertEquals(strtotime(date('Y-m-d H:i:s')), strtotime(date('Y-m-d H:i:s',$last_pull[0])));
        $this->assertEquals(0, $last_pull[1]);

        //设置设备未拉取过该任务
        $this->taskEntity->setIsPull(false);
        //设置设备触达量为0
        $this->taskEntity->setCurNum(0);
        //7、设置设备触达量为0，设置设备未拉取过该任务，校验成功
        $this->assertEquals(true, TouchValidate::run($this->appRequestEntity, $this->taskEntity));
        //8、校验该设备触达量为1
        $this->assertEquals(1, $this->redis->hget('task_info:'.$this->taskEntity->getId(), 'cur_num'));

        //设置设备未拉取过该任务
        $this->taskEntity->setIsPull(false);
        //设置任务类型为普通任务
        $this->taskEntity->setFrequency(C::$TASK_FREQUENCY_GENERAL);
        //设置设备未拉取过普通任务
        $this->appRequestEntity->setIsContainGeneral(false);
        //9、设置任务类型为普通任务，设置设备未拉取过该任务，校验成功
        $this->assertEquals(true, TouchValidate::run($this->appRequestEntity, $this->taskEntity));
        //10、校验设备已经拉取过普通任务
        $this->assertEquals(true, $this->appRequestEntity->getIsContainGeneral(false));

        //设置任务的资源类型为自升级
        $this->taskEntity->setResource(C::$RESOURCE_UPGRADE);

        $this->expectException(UpgradeException::class);
        //11、设置任务资源类型为自升级，抛出自升级异常
        TouchValidate::run($this->appRequestEntity, $this->taskEntity);
    }

    public function tearDown()
    {
        parent::tearDown();

        //清除缓存
        $this->redis->hdel("task_pull_history:".$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId());
        $this->redis->hdel("task_info:".$this->taskEntity->getId(), $this->appRequestEntity->getDeviceId());

    }

}
