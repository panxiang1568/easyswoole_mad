<?php

namespace App\logic;

use App\exception\DataException;
use App\exception\IException;
use App\logic\core\ITask;
use Exception;

/**
 * 核心业务主程序
 * Class Main
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-18 10:39
 * @package App\logic
 */
class Main
{
    /**
     * @var object 子业务类
     */
    private $class;

    /**
     * 初始化子业务类
     * Common constructor.
     * @param ITask $class 子业务类
     */
    public function __construct(ITask $class)
    {
        $this->class = $class;
    }


    /**
     * 通用业务入口方法
     * 1.Request数据处理（解码、转码、验证、过滤）
     * 2.Response数据处理（编码、转码、数据封装、记录日志）
     * 3.异常处理
     * @return bool 结果输出
     */
    public function main()
    {
        $data_exception = false;
        try {
            //执行核心业务流程
            $response = $this->class->main();

        } catch (DataException $e) {
            $data_exception = true;
            $response = $e->getResponse();
        } catch ( IException $e) {
            $response = $e->getResponse();
        }catch ( Exception $e) {
            $response = $e->getMessage();
        }

        if (false === $data_exception) {
            //记录日志
            $this->class->record();
        }


        return $this->class->getResponse()->write($response);
    }

}