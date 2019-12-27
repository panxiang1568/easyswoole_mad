<?php

namespace App\logic\filter;

use App\entity\AppRequestEntity;
use App\model\CommonTools;
use PHPUnit\Framework\TestCase;

class RelateTaskFilterTest extends TestCase
{
    use CommonTools;
    protected $redis;
    protected $appRequestEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=1
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"capability":"01|02|03|04|05|06|08|10","stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(1);
        //获取redis
        $this->redis = self::getRedis();
        //设置任务1，2，3，5，6发布量10，触达量1
        $this->redis->hset('task_info:1', 'max_num', 10);
        $this->redis->hset('task_info:1', 'cur_num', 1);
        $this->redis->hset('task_info:2', 'max_num', 10);
        $this->redis->hset('task_info:2', 'cur_num', 1);
        $this->redis->hset('task_info:3', 'max_num', 10);
        $this->redis->hset('task_info:3', 'cur_num', 1);
        $this->redis->hset('task_info:5', 'max_num', 10);
        $this->redis->hset('task_info:5', 'cur_num', 1);
        $this->redis->hset('task_info:6', 'max_num', 10);
        $this->redis->hset('task_info:6', 'cur_num', 1);

    }

    public function testGetIdsByStatus()
    {

        //设置任务ID集合[1,2,3]
        $task_id = [1,2,3];
        //设置关联任务集为空
        $relateIds = [];
        //1、不存在关联任务集，校验返回的任务集[1,2,3]
        $this->assertEquals([1,2,3], RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds));

        //设置任务ID集合[1,2,3]
        $task_id = [1,2,3];
        //设置关联任务集为[[4,5],[6,7]]
        $relateIds = [[4,5],[6,7]];
        //2、关联任务和任务ID集合无交集，校验返回的任务集[1,2,3]
        $this->assertEquals([1,2,3], RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds));

        //设置任务ID集合[1,2,3,5,6]
        $task_id = [1,2,3,5,6];
        //设置关联任务集为[[1,2],[3,4]]
        $relateIds = [[1,2],[3,4]];
        //3、关联任务和任务ID集合有交集，并且不存在已经被拉取的任务,关联组里的任务触达量小于发布量，校验返回的任务集[5,6,2,3]
        $this->assertEquals(array_values([2,3,5,6]), array_values(RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds)));

        //设置任务ID集合[1,2,3,5,6]
        $task_id = [1,2,3,5,6];
        //设置关联任务集为[[1,2],[3,4]]
        $relateIds = [[1,2],[3,4]];
        //设置任务2的触达量为10，使任务2的触达量=发布量
        $this->redis->hset('task_info:2', 'cur_num', 10);
        //4、关联任务和任务ID集合有交集，并且不存在已经被拉取的任务,任务2已达到最大发布量，关联组里的其他任务触达量小于发布量，校验返回任务集合[5,6,1,3]
        $this->assertEquals(array_values([1,3,5,6]), array_values(RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds)));

        //设置任务ID集合[1,2,3,5,6,7]
        $task_id = [1,2,3,5,6,7];
        //设置关联任务集为[[1,2,5],[3,8]]
        $relateIds = [[1,2,5],[3,8]];
        //设置任务2的触达量为1
        $this->redis->hset('task_info:2', 'cur_num', 1);
        //设置该设备已经拉取过任务1
        $this->redis->hset('task_pull_history:1', 1, '1571278547|0');
        //5、关联任务和任务ID集合有交集，并且该设备已经拉取了任务1，任务1处于发布状态，校验返回任务集合[6,7,1,3]
        $this->assertEquals(array_values([1,3,6,7]), array_values(RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds)));

        //设置任务ID集合[2,3,5,6,7]
        $task_id = [2,3,5,6,7];
        //设置关联任务集为[[1,2,5],[3,8]]
        $relateIds = [[1,2,5],[3,8]];
        //设置该设备已经拉取过任务1
        $this->redis->hset('task_pull_history:1', 1, '1571278547|0');
        //关联任务和任务ID集合有交集，并且该设备已经拉取了任务1，任务1处于初始或者下线状态，校验返回任务集合[6,7,3]
        $this->assertEquals(array_values([3,6,7]), array_values(RelateTaskFilter::run($task_id, $this->appRequestEntity, $relateIds)));

    }

    public function tearDown()
    {
        parent::tearDown();
        //清除缓存
        $this->redis->del('task_info:1');
        $this->redis->del('task_info:2');
        $this->redis->del('task_info:3');
        $this->redis->del('task_info:4');
        $this->redis->del('task_info:5');
        $this->redis->del('task_pull_history:1');

    }
}
