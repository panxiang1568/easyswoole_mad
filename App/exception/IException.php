<?php
namespace App\exception;

/**
 * Interface IException
 * @package App\exception
 */
interface IException
{
    /**
     * 获取输出结果
     * @return mixed
     */
    public function getResponse();
}