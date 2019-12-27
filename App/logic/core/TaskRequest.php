<?php

namespace App\logic\core;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\AppResponseEntity;
use App\entity\IEntity;
use App\entity\TaskEntity;
use App\logic\filter\RelateTaskFilter;
use App\logic\filter\TaskAdvancedFilter;
use App\logic\resource\Dialog;
use App\logic\resource\Install;
use App\logic\resource\InstallAndLaunch;
use App\logic\resource\Launch;
use App\logic\resource\Link;
use App\logic\resource\Notification;
use App\logic\resource\Uninstall;
use App\logic\resource\Upgrade;
use App\logic\validate\DeviceValidate;
use App\model\Channel;
use App\model\CommonTools;
use App\model\Cycle;
use App\model\Device;
use App\model\Group;
use App\model\IP;
use App\model\Silent;
use App\model\Task;
use App\model\TaskDevice;
use App\model\TaskRelate;
use App\utils\Log;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use ryan\tools\Http;
use Throwable;
use Swoole\Coroutine as Co;

/**
 * 任务请求核心类
 * Class TaskRequest
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-18 10:38
 * @package App\logic\core
 */
class TaskRequest implements ITask
{
	use CommonTools;
    private $response;
    private $request;

    private $requestEntity;
    private $responseEntity;

    /**
     * TaskRequest constructor.
     * @param Request $request Swoole Request
     * @param Response $response Swoole Response
     */
    public function __construct(Request $request, Response $response)
    {
        /**
         * 设置Swoole Request
         */
        $this->request = $request;
        /**
         * 设置Swoole Response
         */
        $this->response = $response;

    }

    /**
     * 初始化数据
     */
    public function init(): void
    {
        /**
         * 初始化AppRequest Entity
         */
        $this->requestEntity = new AppRequestEntity($this->request->getBody()->__toString());

        //设置IP和scheme
        $this->getIPAndScheme($this->request->getHeaders());
        
        /**
         * 初始化AppResponse Entity
         */
        $this->responseEntity = new AppResponseEntity();
        $this->responseEntity->setIsBase64($this->requestEntity->isBase64());
        $this->responseEntity->setIsMapData($this->requestEntity->isMapData());
        $this->responseEntity->setVersion($this->requestEntity->getVersion());
        $this->responseEntity->setSession(C::$SESSION_APP_RSP);
        $this->responseEntity->setCode(C::$CODE_1500);
        $this->responseEntity->setMsg(C::$SUCCESS);
    }

    /**
     * 核心入口函数
     * @throws Throwable
     */
    public function main(): string
    {
        //初始化数据
        $this->init();

        /**
         * 解析IP，获取地域数据
         * 大陆、国家、区域、城市
         */
        $this->parseIP();

        /**
         * 获取心跳周期
         */
        $cycle = Cycle::get($this->getRequestEntity());
        
        $this->getResponseEntity()->setCycle($cycle);

        $this->getRequestEntity()->setCycle($cycle);

        /**
         * 获取渠道ID
         */
        $this->getRequestEntity()->setChannelId(Channel::get($this->getRequestEntity()));

        /**
         * 获取设备信息
         * 设备ID、注册时间、测试状态
         */
        $this->getDeviceInfo();

        /**
         * 获取静默期
         */
        $this->getRequestEntity()->setSilent(Silent::get($this->getRequestEntity()));

        /**
         * 设备静默期校验
         */
        DeviceValidate::isSilent($this->getRequestEntity());


        /**
         * 获取任务ID集合
         * 测试设备返回测试中的任务ID集合
         * 非测试设备返回发布中的任务ID结合
         */
        $this->getRequestEntity()->setTaskIds(
            Task::getIdsByStatus($this->getRequestEntity()->getisTest() ? C::$TASK_STATUS_TESTING : C::$TASK_STATUS_ONLINE, $this->getRequestEntity())
        );


        /**
         * 关联任务过滤任务ID集合
         */
        $this->getRequestEntity()->setTaskIds(
            RelateTaskFilter::run(
                $this->getRequestEntity()->getTaskIds(), $this->getRequestEntity(), TaskRelate::get()
            )
        );

        /**
         * 获取设备所属分组、所属任务信息
         */
        $this->getDeviceGroupAndTaskIds();

        /**
         * 高级过滤规则
         */
        $taskEntitySet = TaskAdvancedFilter::run($this->getRequestEntity());


        /**
         * 根据可发布的TaskID构建Response
         */
        $this->buildResponse($taskEntitySet, $this->getRequestEntity());
        /**
         * 序列化结果
         */
        return $this->getResponseEntity()->serialize();
    }

    /**
     * 记录日志
     */
    public function record()
    {
        /**
         * 记录日志信息时，序列化不需要转码
         */
        $this->getRequestEntity()->setIsMapData(false);
        $data = $this->getRequestEntity()->serialize();
        $log_data = $this->getRequestEntity()->isBase64() ? base64_decode($data) : $data;
        /**
         * 写入日志文件
         */
//        Log::write(Config::getInstance()->getConf('task_request').'statis_pull_'.date('Ymd').'.log', '[' . date('Y-m-d H:i:s'). '] ' . $log_data . PHP_EOL);

	    CommonTools::ampqSend($log_data);

//        Log::write('./Log/task_request.log', '[' . date('Y-m-d H:i:s'). '] ' . $log_data . PHP_EOL);
    }


    /**
     * 获取AppRequest Entity
     * @return AppRequestEntity
     */
    public function getRequestEntity(): AppRequestEntity
    {
        return $this->requestEntity;
    }

    /**
     * 获取AppResponse Entity
     * @return AppResponseEntity
     */
    public function getResponseEntity(): IEntity
    {
        return $this->responseEntity;
    }


    /**
     * 获取Swoole Request对象
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * 获取Swoole Response对象
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * 解析IP，获取地域数据
     */
    private function parseIP()
    {
        /**
         * $continent 大陆编码
         * $country 国家编码
         * $region 大洲或省份
         * $city 城市
         */
        list($continent, $country, $region, $city) = IP::parse($this->getRequestEntity()->getIp());
        /**
         * 将地域相关信息配置AppRequestEntity
         */
        $this->getRequestEntity()->setContinent($continent);
        $this->getRequestEntity()->setCountry($country);
        $this->getRequestEntity()->setRegion($region);
        $this->getRequestEntity()->setCity($city);

    }

    /**
     * 获取设备信息
     * @throws Throwable
     */
    private function getDeviceInfo()
    {
        /**
         * $dev_id 设备唯一ID
         * $register_time 设备注册时间
         */
        list($deviceId, $registerTime) = Device::get($this->getRequestEntity());

        /**
         * 获取设备的测试状态
         */
        $isTest = DeviceValidate::isTest($this->getRequestEntity());

        /**
         * 将设备相关信息配置AppRequestEntity
         */
        $this->getRequestEntity()->setDeviceId($deviceId);
        $this->getRequestEntity()->setRegisterTime($registerTime);
        $this->getRequestEntity()->setIsTest($isTest);

    }

    /**
     * 根据任务ID集合 构建Response
     *
     * @param array|null $taskEntitySet 任务实体集合
     * @param AppRequestEntity $obj
     */
    private function buildResponse(?array $taskEntitySet, AppRequestEntity $obj)
    {
        //根据资源类型封装数据
        $appList = $link = $capList = [];
        $link = null;
        //获取链接域名
        $url_domain = Config::getInstance()->getConf('url_domain');
        //获取请求协议类型
        $scheme = $obj->getScheme();
        //获取设备号
        $correlator = $obj->getDevid();

        foreach ($taskEntitySet as $taskEntity) {
            if ($taskEntity instanceof TaskEntity) {

                $response_task                       = [];
                $response_task[ C::$KEY_TASK_ID ]    = $taskEntity->getId();
                $response_task[ C::$KEY_CORRELATOR ] = $correlator;
                $response_task[ C::$KEY_OPERATION ]  = $taskEntity->getResource();

                //转换资源信息中的json为数组
                $resource_info = $taskEntity->getResourceInfo();

                switch ($taskEntity->getResource()) {

                    case C::$RESOURCE_INSTALL:
                        //下载安装
                        $appList[] = Install::run($response_task, $resource_info, $scheme, $url_domain);
                        break;

                    case C::$RESOURCE_INSTALL_AND_LAUNCH:
                        //下载安装并启动
                        $appList[] = InstallAndLaunch::run($response_task, $resource_info, $scheme, $url_domain);
                        break;

                    case C::$RESOURCE_LAUNCH:
                        //启动
                        $appList[] = Launch::run($response_task, $resource_info);
                        break;

                    case C::$RESOURCE_UNINSTALL:
                        //卸载
                        $capList[] = Uninstall::run($response_task, $resource_info);
                        break;

                    case C::$RESOURCE_LINK:
                        //链接
						if (null === $link) {
							$link = Link::run($response_task, $resource_info, $scheme);
						}
                        break;

                    case C::$RESOURCE_DIALOG:
                        //弹框
                        $capList[] = Dialog::run($response_task, $resource_info, $scheme, $url_domain);
                        break;

                    case C::$RESOURCE_NOTIFICATION:
                        //状态栏
                        $capList[] = Notification::run($response_task, $resource_info, $scheme, $url_domain);
                        break;

                    case C::$RESOURCE_UPGRADE:
                        //自升级
                        $capList[] = Upgrade::run($response_task, $resource_info, $scheme, $url_domain);
                        break;

                    case C::$RESOURCE_DOWNLOAD:
                        //文件下发
                        break;
                }

            }
        }

        //根据封装结果 设置Response
        $this->getResponseEntity()->setApplist( $appList );

        $this->getResponseEntity()->setLink( $link );

        $this->getResponseEntity()->setCaplist( $capList );
    }

    /**
     * 获取设备信息
     * @throws Throwable
     */
    private function getDeviceGroupAndTaskIds()
    {
        /**
         * 将设备相关信息配置AppRequestEntity
         */
        $this->getRequestEntity()->setGroupIds(Group::get($this->getRequestEntity()));
        $this->getRequestEntity()->setTaskDeviceIds(TaskDevice::get($this->getRequestEntity()));

    }

    /**
     * 设置request实体中IP和scheme的值
     *
     * @param $header_info
     */
    private function getIPAndScheme($header_info)
    {
        $isset_ip = $this->getRequestEntity()->getIP();

        if (!$isset_ip) {
            $this->getRequestEntity()->setIp(Http::ip($header_info));
        }

        $this->getRequestEntity()->setScheme(Http::scheme($header_info));
    }

}