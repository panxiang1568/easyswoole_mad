<?php

namespace App\logic\validate\task;


use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;
use PHPUnit\Framework\TestCase;

class AreaAdvancedValidateTest extends TestCase
{

    protected $appRequestEntity;
    protected $taskEntity;

    public function setUp()
    {

        //生成Entity，设置设备ID=1，渠道ID=2,处于发布状态的任务集合[3]
        $this->appRequestEntity = new AppRequestEntity('{"version":"M4.3","session":"APP-REQ","carrier":{"appid":"6ruzzdkgm3vrl6admdvqxk1t","pkgname":"com.redstone.ota.ui","channel":"wingtechA4s","version":"4.2.35","silent":1,"stub_version":"4.2.35"}}');
        $this->appRequestEntity->setDeviceId(1);
        $this->appRequestEntity->setChannelId(2);
        $this->appRequestEntity->setRegion('');
        $this->appRequestEntity->setCity('');
        $this->appRequestEntity->setTaskIds([3]);

        //生成TaskEntity ,设置任务ID=3，任务国家校验规则为白名单，任务国家配置为'CN,JP'
        $this->taskEntity = new TaskEntity();
        $this->taskEntity->setId(3);
        //设置任务的高级配置信息
        $this->taskEntity->setAdvanceConfig('');
        //设置任务的国家校验规则为白名单
        $this->taskEntity->setCountryRule(C::$TASK_WHITE);
        //设置任务的国家配置
        $this->taskEntity->setCountry('CN,JP');

    }

    public function testRun()
    {

        //设置设备的国家编码为'AU'
        $this->appRequestEntity->setCountry('AU');
        //1、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'', 任务的城市配置：'', 设备国家编码为'AU'，校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //2、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'', 任务的城市配置：''， 设备国家编码为'CN'，校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'shandong'
        $this->appRequestEntity->setRegion('shandong');
        //3、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'', 设备国家编码为'CN'，设备省份为'shandong', 校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //4、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'', 设备国家编码为'CN'，设备省份为'heibei', 校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //设置设备的城市为'baoding'
        $this->appRequestEntity->setCity('baoding');
        //5、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'heibei', 设备城市为'baoding', 校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //设置设备的城市为'shijiazhuang'
        $this->appRequestEntity->setCity('shijiazhuang');
        //6、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'heibei', 设备城市为'shijiazhuang',校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei,shandong'
        $this->taskEntity->setRegion('hebei,shandong');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('shandong');
        //设置设备的城市为'baoding'
        $this->appRequestEntity->setCity('weihai');
        //7、任务的国家校验规则为白名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei,shandong', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'shandong', 设备城市为'weihai', 校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的国家校验规则为黑名单
        $this->taskEntity->setCountryRule(C::$TASK_BLACK);
        //设置任务的省份配置为[]
        $this->taskEntity->setRegion('');
        //设置任务的城市配置为[]
        $this->taskEntity->setCity('');
        //设置设备的省份为''
        $this->appRequestEntity->setRegion('');
        //设置设备的城市为''
        $this->appRequestEntity->setCity('');


        //设置设备的国家编码为'AU'
        $this->appRequestEntity->setCountry('AU');
        //7、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'', 任务的城市配置：'', 设备国家编码为'AU'，校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //8、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'', 任务的城市配置：'', 设备国家编码为'CN'，校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //9、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'', 设备国家编码为'CN'，设备省份为'heibei',校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'shandong'
        $this->appRequestEntity->setRegion('shandong');
        //10、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'', 设备国家编码为'CN'，设备省份为'shandong',校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei'
        $this->taskEntity->setRegion('hebei');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //设置设备的城市为'baoding'
        $this->appRequestEntity->setCity('baoding');
        //11、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'heibei', 设备城市为'baoding', 校验成功
        $this->assertEquals(true, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为['hebei']
        $this->taskEntity->setRegion('hebei');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('hebei');
        //设置设备的城市为'shijiazhuang'
        $this->appRequestEntity->setCity('shijiazhuang');
        //12、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'heibei', 设备城市为'shijiazhuang', 校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

        //设置任务的省份配置为'hebei,shandong'
        $this->taskEntity->setRegion('hebei,shandong');
        //设置任务的城市配置为'hebei-shijiazhuang'
        $this->taskEntity->setCity('hebei-shijiazhuang');
        //设置设备的国家编码为'CN'
        $this->appRequestEntity->setCountry('CN');
        //设置设备的省份为'hebei'
        $this->appRequestEntity->setRegion('shandong');
        //设置设备的城市为'shijiazhuang'
        $this->appRequestEntity->setCity('weihai');
        //12、任务的国家校验规则为黑名单，任务的国家配置：'CN，JP', 任务的省份配置：'hebei,shandong', 任务的城市配置：'hebei-shijiazhuang', 设备国家编码为'CN'，设备省份为'shandong', 设备城市为'weihai', 校验失败
        $this->assertEquals(false, AreaAdvancedValidate::run($this->appRequestEntity, $this->taskEntity));

    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
