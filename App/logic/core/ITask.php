<?php

namespace App\logic\core;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

/**
 * 任务相关接口
 * Interface ITask
 * @package App\logic\core
 */
interface ITask
{

    public function __construct(Request $request, Response $response);

    /**
     * 初始化数据
     */
    public function init(): void;

    /**
     * 核心入口函数
     * @return string
     */
    public function main(): string;

    /**
     * 记录日志
     * @return mixed
     */
    public function record();

    /**
     * 获取Swoole Response
     * @return Response
     */
    public function getResponse(): Response;

    /**
     * 获取Swoole Request
     * @return Request
     */
    public function getRequest(): Request;

}