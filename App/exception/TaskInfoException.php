<?php

namespace App\exception;

use RuntimeException;

/**
 * 任务详细信息异常类
 * Class TaskInfoException
 * @package App\exception
 */
class TaskInfoException extends RuntimeException implements IException
{
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
	public function getResponse(){}
}