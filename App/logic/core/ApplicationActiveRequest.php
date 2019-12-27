<?php

namespace App\logic\core;

use App\entity\ApplicationActiveResponseEntity;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use App\model\ApplicationActive;
use Throwable;

/**
 * 应用保活核心类
 * Class ApplicationActive
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-18 10:38
 * @package App\logic\core
 */
class ApplicationActiveRequest implements ITask
{
    private $response;
    private $request;

    private $applicationActiveResponseEntity;

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
         * 初始化ApplicationActiveeResponse Entity
         */
        $this->applicationActiveResponseEntity = new ApplicationActiveResponseEntity();
        $this->applicationActiveResponseEntity->setIsBase64(true);
        $this->applicationActiveResponseEntity->setIsMapData(true);
    }

	/**
	 * 核心入口函数
	 * @return string
	 * @throws Throwable
	 */
    public function main(): string
    {
        /**
         * 初始化数据
         */
        $this->init();
        /**
         * 获取应用保活信息
         */
        $this->getResponseEntity()->setApplicationActive(ApplicationActive::get());

        /**
         * 返回序列化结果
         */
        return $this->getResponseEntity()->serialize();

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
     * 记录日志
     */
    public function record()
    {

    }

    /**
     * 获取Swoole Request
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * 获取响应实体
     */
    public function getResponseEntity(): ApplicationActiveResponseEntity
    {
        return $this->applicationActiveResponseEntity;
    }
}