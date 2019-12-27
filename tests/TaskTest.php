<?php

namespace App\model;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\exception\TaskInfoException;
use App\exception\ValidateException;
use PHPUnit\Framework\TestCase;


class TaskTest extends TestCase
{
	use CommonTools;

	private $redis;
	private $appRequestEntity;
	private $task_id;

	public function setUp()
	{
		$this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
		$this->appRequestEntity->setChannelId(1);
		$this->redis = self::getRedis();

	}

	public function testGet()
	{
		$this->expectException(TaskInfoException::class);
		Task::get( $this->task_id );
		//设任务ID为12
		$this->task_id = 12;

		//数据填充
		$this->redis->hSet('task_info:' . $this->task_id, 'resource_info', '{"icon":"d","objecturi":"\/Uploads\/mobile\/app\/72ab19c30fca199d2dd58f8e0f0c9891.apk","net_state":1,"appname":"\u5370\u5ea6-0321-9Apps(\u535c)","pkgname":"com.mobile.indiapp","objectsize":4867096,"version":"3.3.3.700","versionCode":"163","start":{"class":"com.mobile.indiapp.activity.WelcomePageActivity","type":"activity","action":"android.intent.action.MAIN","extra":[]}}');
		$this->redis->hSet('task_info:' . $this->task_id, 'advance_config', '{"country_range":"white","country_ids":"5","prov_ids":"","city_ids":"","operator_range":"all","operator_ids":"","model_range":"all","model_ids":"","group_ids":""}');
		$this->redis->hSet('task_info:' . $this->task_id, 'max_num', 420000);
		$this->redis->hSet('task_info:' . $this->task_id, 'cur_num', 388943);
		$this->redis->hSet('task_info:' . $this->task_id, 'interval', '1,3');
		$this->redis->hSet('task_info:' . $this->task_id, 'area', 2);
		$this->redis->hSet('task_info:' . $this->task_id, 'frequency', 2);
		$this->redis->hSet('task_info:' . $this->task_id, 'resource', 2);
		$this->redis->hSet('task_info:' . $this->task_id, 'custom_device', 0);
		$this->redis->hSet('task_info:' . $this->task_id, 'id', $this->task_id);


		$res = Task::get( $this->task_id );
		//多项测试
		$this->assertIsObject($res,'The result is not an Object');

		//检查结果是否涵盖所有属性
		$this->assertObjectHasAttribute('id',
			$res, 'Attribute id not exist');

		$this->assertObjectHasAttribute('resource_info',
			$res, 'Attribute resource_info not exist');

		$this->assertObjectHasAttribute('advance_config',
			$res, 'Attribute advance_config not exist');

		$this->assertObjectHasAttribute('max_num',
			$res, 'Attribute max_num not exist');

		$this->assertObjectHasAttribute('cur_num',
			$res, 'Attribute cur_num not exist');

		$this->assertObjectHasAttribute('interval',
			$res, 'Attribute interval not exist');

		$this->assertObjectHasAttribute('area',
			$res, 'Attribute area not exist');

		$this->assertObjectHasAttribute('frequency',
			$res, 'Attribute frequency not exist');

		$this->assertObjectHasAttribute('resource',
			$res, 'Attribute resource not exist');

		$this->assertObjectHasAttribute('custom_device',
			$res, 'Attribute custom_device not exist');
	}

    public function testGetIdsByStatus()
    {
        $this->expectException(ValidateException::class);

        //1、设备为测试设备，无测试状态任务时，校验抛异常
        Task::getIdsByStatus(C::$TASK_STATUS_TESTING,$this->appRequestEntity);

        //2、设备为正式设备，无发布状态任务时，校验抛异常
        Task::getIdsByStatus(C::$TASK_STATUS_ONLINE,$this->appRequestEntity);

        //设置渠道1下的测试状态的任务集合为1,2,3
        $this->redis->hset('task_list:test', 1, '1,2,3');
        //3、设备为测试设备，校验返回任务集合[1,2,3]
        $this->assertEquals([1,2,3], Task::getIdsByStatus(C::$TASK_STATUS_TESTING,$this->appRequestEntity));

        //设置渠道1下的发布状态的任务集合为4,5,6
        $this->redis->hset('task_list:online', 1, '4,5,6');
        //4、设备为正式设备，校验返回任务集合为[4,5,6]
        $this->assertEquals([4,5,6], Task::getIdsByStatus(C::$TASK_STATUS_ONLINE,$this->appRequestEntity));

    }

	public function tearDown()
	{
		parent::tearDown();
		//清除缓存
		$this->redis->del('task_info:' . $this->task_id);
        $this->redis->hdel('task_list:test',1);
        $this->redis->hdel('task_list:online',1);
	}
}
