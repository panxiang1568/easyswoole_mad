<?php

namespace App\controller;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

/**
 * 路由类
 * Class Router
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:12
 * @package App\controller
 */
class Router extends AbstractRouter
{
    public function initialize(RouteCollector $routeCollector)
    {
    	$routeCollector->get('/send', '/index/send');
    	$routeCollector->get('/receive', '/index/receive');
        /**
         * 任务检测
         */
        $routeCollector->post('/msg/pull', '/api/taskRequest');
        /**
         * 事件报告和任务状态上报
         */
        $routeCollector->post('/msg/post', '/api/eventAndStatusReport');
        /**
         * 应用保活
         */
        $routeCollector->post('/msg/whitelist', '/api/applicationActive');
    }
}