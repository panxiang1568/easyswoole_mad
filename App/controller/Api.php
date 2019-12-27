<?php

namespace App\controller;

use App\logic\core\ApplicationActiveRequest;
use App\logic\core\EventAndStatusReport;
use App\logic\Main;
use App\logic\core\TaskRequest;
use EasySwoole\Http\AbstractInterface\Controller;

class Api extends Controller
{
    /**
     * 默认请求入口
     * @return bool
     */
    public function index()
    {
        return $this->response()->write("API");
    }

    /**
     * 任务检测
     * @return bool
     */
    public function taskRequest()
    {
        return (
        new Main(
            new TaskRequest(
                $this->request(), $this->response()
            )
        )
        )->main();
    }

	/**
	 * 事件上报和状态上报
	 * @return bool
	 */
    public function eventAndStatusReport()
    {
	    return (
	    new Main(
		    new EventAndStatusReport(
			    $this->request(), $this->response()
		    )
	    )
	    )->main();

    }

    /**
     * 应用保活
     * @return bool
     */
    public function applicationActive()
    {
        return (
        new Main(
            new ApplicationActiveRequest(
                $this->request(), $this->response()
            )
        )
        )->main();
    }
}