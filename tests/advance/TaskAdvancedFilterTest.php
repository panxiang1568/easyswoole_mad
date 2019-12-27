<?php

namespace App\logic\filter;

use App\entity\AppRequestEntity;
use App\model\CommonTools;
use App\model\Task;
use PHPUnit\Framework\TestCase;
use EasySwoole\HttpClient\HttpClient;

class TaskAdvancedFilterTest extends TestCase
{
    use CommonTools;
    protected $appRequestEntity;
    protected $taskEntity;
    protected $redis;

    public function setUp()
    {
        //redis
        $this->redis = self::getRedis();

        //下载安装
        $resource1 = [
            'id' => 101,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 1,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"wifionly":"0","objecturi":"\/Uploads\/mad_new\/a8bf65d2becf5b8356e1ddcb51046b95.apk","icon":"undefined","appname":"like","pkgname":"video.like","objectsize":"59913749","version":"3.1.7","version_code":"1800","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.yy.iheima.startup.MainActivity","extra":[]}}'
        ];

        //下载安装并启动
        $resource2 = [
            'id' => 102,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 2,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"wifionly":"0","objecturi":"\/Uploads\/mad_new\/99c6adf912fb05f5a90fb725a13ecdc2.apk","icon":"undefined","appname":"87","pkgname":"com.ushaqi.zhuishushenqi.adfree","objectsize":"20400954","version":"2.4.1","version_code":"1141","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.ushaqi.zhuishushenqi.ui.SplashActivity","extra":[]}}'
        ];

        //启动
        $resource3 = [
            'id' => 103,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 3,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"pkgname":"com.frego.flashlight","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.frego.flashlight.MainActivity","extra":[]}}'
        ];

        //卸载
        $resource4 = [
            'id' => 104,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 4,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"pkgname":"com.redstone.phone.guard"}'
        ];

        //打开链接
        $resource5 = [
            'id' => 105,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 5,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"objecturi":"http:\/\/www.baidu.com"}'
        ];

        //弹框01-链接
        $resource61 = [
            'id' => 106,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 6,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"type_action":"01","title":"111222","icon":"\/Uploads\/Default\/20191122\/0111-1100-001jpg.jpg","objecturi":"http:\/\/www.baidu.com","brief":"ddd"}'
        ];

        //弹框02-应用
        $resource62 = [
            'id' => 107,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 6,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"type_action":"02","title":"like","icon":"\/Uploads\/Default\/20191125\/TIM\u56fe\u724720190809102240.jpg","start_type":"1","objecturi":"\/Uploads\/mad_new\/d5d53f004b532d1048b7ed3323236598.apk","apk_icon":"undefined","appname":"like","pkgname":"com.github.shadowsocks","objectsize":"9378326","version":"4.5.7","version_code":"4050700","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.github.shadowsocks.MainActivity","extra":[]},"brief":""}'
        ];

        //状态栏01-链接
        $resource71 = [
            'id' => 108,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 7,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"type_action":"01","img_type":"1","status_pic":"\/Uploads\/Default\/20191122\/0111-1027-001.jpg","objecturi":"http:\/\/www.baidu.com"}'
        ];

        //状态栏02-应用
        $resource72 = [
            'id' => 109,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 7,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"type_action":"02","img_type":"2","title":"test_1","brief":"test_2","status_icon":"\/Uploads\/Default\/20191127\/1574826010750.jpg","start_type":"1","objecturi":"\/Uploads\/mad_new\/168e39388e57f9ea4bdbe6572e112725.apk","apk_icon":"undefined","appname":"testapk","pkgname":"com.test.a","objectsize":"14788","version":"1.0","version_code":"1","start":{"type":"activity","action":"android.intent.action.MAIN","class":"com.test.a.TestActivity","extra":[]}}'
        ];

        //自升级
        $resource8 = [
            'id' => 110,
            'area' => 1,
            'custom_device' => 0,
            'resource' => 8,
            'frequency' => 3,
            'interval' => '',
            'max_num' => 10,
            'cur_num' => 1,
            'advance_config' => '{"country_rule":"white","country":"CN","model_rule":"white","model":"F2,K3S","group":"2"}',
            'resource_info' => '{"impl":"4.2.71","stub":"1","objecturi":"\/Uploads\/mad_new\/jar\/827c34475d2ec1e91dc040e8ff1c8313.jar","objectsize":"63419"}'
        ];

        //添加任务详情缓存
        $this->redis->hMSet('task_info:101',$resource1);
        $this->redis->hMSet('task_info:102',$resource2);
        $this->redis->hMSet('task_info:103',$resource3);
        $this->redis->hMSet('task_info:104',$resource4);
        $this->redis->hMSet('task_info:105',$resource5);
        $this->redis->hMSet('task_info:106',$resource61);
        $this->redis->hMSet('task_info:107',$resource62);
        $this->redis->hMSet('task_info:108',$resource71);
        $this->redis->hMSet('task_info:109',$resource72);
        $this->redis->hMSet('task_info:110',$resource8);

        //设置产品'lgh2qmtd9soiso01qecfyytv'、渠道'test'
        $this->redis->hset('appkey:lgh2qmtd9soiso01qecfylln', 'test', 1000);

        //设置设备'IMEI:999963030012461' 渠道为'test'
        $this->redis->hset('device:1000', 'IMEI:999963030012461', '10|1562570025');

        //静默期
        $this->redis->hset('silent', 'lgh2qmtd9soiso01qecfylln', 0);

        //添加发布任务集合
        $this->redis->hset('task_list:online', 1000, '101,102,103,104,105,106,107,108,109,110');

        //分组设备
        $this->redis->hset('group_device', 'IMEI:999963030012461', '1,2,3');


    }

    public function testRun()
    {

        //设置产品'lgh2qmtd9soiso01qecfyytv'、渠道'test'
//        $this->redis->hset('appkey:lgh2qmtd9soiso01qecfyytv', 'test', 1);
//
//        //设置设备'IMEI:999963030012461' 渠道为'test'
//        $this->redis->hset('device:1', 'IMEI:999963030012461', '1|1562570025');
//        //设置设备'IMEI:999963030012462' 渠道为'test'
//        $this->redis->hset('device:1', 'IMEI:999963030012462', '2|1562570025');
//        //设置设备'IMEI:999963030012463' 渠道为'test'
//        $this->redis->hset('device:1', 'IMEI:999963030012463', '3|1562570025');
//        //设置设备'IMEI:999963030012464' 渠道为'test'
//        $this->redis->hset('device:1', 'IMEI:999963030012464', '4|1562570025');
//
//        //设置测试设备'IMEI:999963030012465' 渠道为'test'
//        $this->redis->hset('testdev:1', 'IMEI:999963030012465');
//
//        //静默期
//        $this->redis->hset('silent', 'lgh2qmtd9soiso01qecfyytv_test_M836', 10);
//
//        //心跳周期
//        $this->redis->hset('cycle', 'lgh2qmtd9soiso01qecfyytv_test_M836', 2);
//
//        //分组设备
//        $this->redis->hset('group_device', 1, '1,2,3');
//
//        //任务设备
//        $this->redis->hset('task_device', 1, '1,2,3');


        $url = '127.0.0.1:9501/msg/pull';
        $request = '{"debug_ip":"124.200.182.130","d0":"M4.6","d1":"s20","d2":"IMEI:999963030012461","d3":"XeXBYZxt+\/MBAFkiGNatB+5F","d4":"Coolpad","d5":"F2","d6":"6.0","d8":"zh-CN","d9":"unknown","dd":"unknown","de":"data","df":9420439552,"dg":12042002432,"dh":"D0:37:42:56:4D:4D","dn":"445d8ae83314b1d1","c0":{"c1":"lgh2qmtd9soiso01qecfylln","c2":"com.example.meteor","c3":"test","c4":"4.2.70","c5":1,"c6":"01|02|03|04|05|06|07|08|10","c7":"4.0.09"}}';
        $client = new HttpClient($url);
        $response = $client->post($request);
        $this->assertEquals("{\"a5\":\"M4.6\",\"r1\":\"1500\",\"d1\":\"APP-RSP\",\"r2\":\"8\",\"a21\":[{\"a2\":\"110\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"8\",\"a5\":\"4.2.71\",\"a8\":\"63419\",\"a7\":\"http:\/\/mobile.panjiachun.com\/Uploads\/mad_new\/jar\/827c34475d2ec1e91dc040e8ff1c8313.jar\"}],\"msg\":\"200\"}", $response->getBody());


        $this->redis->hset('task_list:online', 1000, '101,102,103,104,105,106,107,108,109');
        $url = '127.0.0.1:9501/msg/pull';
        $request = '{"debug_ip":"124.200.182.130","d0":"M4.6","d1":"s20","d2":"IMEI:999963030012461","d3":"XeXBYZxt+\/MBAFkiGNatB+5F","d4":"Coolpad","d5":"F2","d6":"6.0","d8":"zh-CN","d9":"unknown","dd":"unknown","de":"data","df":9420439552,"dg":12042002432,"dh":"D0:37:42:56:4D:4D","dn":"445d8ae83314b1d1","c0":{"c1":"lgh2qmtd9soiso01qecfylln","c2":"com.example.meteor","c3":"test","c4":"4.2.70","c5":1,"c6":"01|02|03|04|05|06|07|08|10","c7":"4.0.09"}}';
        $client = new HttpClient($url);
        $response = $client->post($request);
        $this->assertEquals("{\"a5\":\"M4.6\",\"r1\":\"1500\",\"d1\":\"APP-RSP\",\"r2\":\"8\",\"a0\":[{\"a2\":\"101\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"1\",\"a3\":\"video.like\",\"a4\":\"like\",\"a5\":\"3.1.7\",\"a20\":\"1800\",\"a8\":\"59913749\",\"a6\":\"\",\"a24\":\"false\",\"a7\":\"http:\/\/mobile.panjiachun.com\/Uploads\/mad_new\/a8bf65d2becf5b8356e1ddcb51046b95.apk\",\"a9\":\"http:\/\/mobile.panjiachun.comundefined\"},{\"a2\":\"102\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"2\",\"a3\":\"com.ushaqi.zhuishushenqi.adfree\",\"a4\":\"87\",\"a5\":\"2.4.1\",\"a20\":\"1141\",\"a8\":\"20400954\",\"a6\":\"\",\"a24\":\"false\",\"a10\":{\"a11\":\"activity\",\"a12\":\"android.intent.action.MAIN\",\"a13\":\"com.ushaqi.zhuishushenqi.ui.SplashActivity\",\"a14\":[]},\"a7\":\"http:\/\/mobile.panjiachun.com\/Uploads\/mad_new\/99c6adf912fb05f5a90fb725a13ecdc2.apk\",\"a9\":\"http:\/\/mobile.panjiachun.comundefined\"},{\"a2\":\"103\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"3\",\"a3\":\"com.frego.flashlight\",\"a5\":\"1.0\",\"a20\":\"1\",\"a10\":{\"a11\":\"activity\",\"a12\":\"android.intent.action.MAIN\",\"a13\":\"com.frego.flashlight.MainActivity\",\"a14\":[]}}],\"l0\":{\"a2\":\"105\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"5\",\"a7\":\"http:\/\/www.baidu.com\"},\"a21\":[{\"a2\":\"104\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"4\",\"a3\":\"com.redstone.phone.guard\"},{\"a2\":\"106\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"6\",\"a6\":\"ddd\",\"a22\":\"111222\",\"a4\":\"\",\"a12\":\"01\",\"a9\":\"http:\/\/mobile.panjiachun.com\/Uploads\/Default\/20191122\/0111-1100-001jpg.jpg\",\"a7\":\"http:\/\/www.baidu.com\"},{\"a2\":\"107\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"6\",\"a6\":\"\",\"a22\":\"like\",\"a4\":\"like\",\"a12\":\"02\",\"a9\":\"http:\/\/mobile.panjiachun.com\/Uploads\/Default\/20191125\/TIM\u56fe\u724720190809102240.jpg\",\"a8\":\"9378326\",\"a5\":\"4.5.7\",\"a20\":\"4050700\",\"a3\":\"com.github.shadowsocks\",\"a7\":\"http:\/\/mobile.panjiachun.com\/Uploads\/mad_new\/d5d53f004b532d1048b7ed3323236598.apk\",\"a10\":{\"a11\":\"activity\",\"a12\":\"android.intent.action.MAIN\",\"a13\":\"com.github.shadowsocks.MainActivity\",\"a14\":[]}},{\"a2\":\"108\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"7\",\"a6\":null,\"a22\":null,\"a4\":\"\",\"a12\":\"01\",\"a16\":\"http:\/\/mobile.panjiachun.com\/Uploads\/Default\/20191122\/0111-1027-001.jpg\",\"a7\":\"http:\/\/www.baidu.com\"},{\"a2\":\"109\",\"a1\":\"IMEI:999963030012461\",\"a15\":\"7\",\"a6\":\"test_2\",\"a22\":\"test_1\",\"a4\":\"testapk\",\"a12\":\"02\",\"a9\":\"http:\/\/mobile.panjiachun.com\/Uploads\/Default\/20191127\/1574826010750.jpg\",\"a8\":\"14788\",\"a5\":\"1.0\",\"a20\":\"1\",\"a3\":\"com.test.a\",\"a7\":\"http:\/\/mobile.panjiachun.com\/Uploads\/mad_new\/168e39388e57f9ea4bdbe6572e112725.apk\",\"a10\":{\"a11\":\"activity\",\"a12\":\"android.intent.action.MAIN\",\"a13\":\"com.test.a.TestActivity\",\"a14\":[]}}],\"msg\":\"200\"}", $response->getBody() );




    }

    public function tearDown()
    {
        parent::tearDown();

        //清除缓存
        $this->redis->del("task_info:101");
        $this->redis->del("task_info:102");
        $this->redis->del("task_info:103");
        $this->redis->del("task_info:104");
        $this->redis->del("task_info:105");
        $this->redis->del("task_info:106");
        $this->redis->del("task_info:107");
        $this->redis->del("task_info:108");
        $this->redis->del("task_info:109");
        $this->redis->del("task_info:110");

        $this->redis->del("task_pull_history:101");
        $this->redis->del("task_pull_history:102");
        $this->redis->del("task_pull_history:103");
        $this->redis->del("task_pull_history:104");
        $this->redis->del("task_pull_history:105");
        $this->redis->del("task_pull_history:106");
        $this->redis->del("task_pull_history:107");
        $this->redis->del("task_pull_history:108");
        $this->redis->del("task_pull_history:109");
        $this->redis->del("task_pull_history:110");

        $this->redis->del("appkey:lgh2qmtd9soiso01qecfylln");
        $this->redis->del("device:1000");
        $this->redis->del("silent");
        $this->redis->del("task_list:online");
        $this->redis->del("group_device");

    }
}
