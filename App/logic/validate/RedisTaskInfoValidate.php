<?php

namespace App\logic\validate;

use App\constant\C;
use App\exception\TaskInfoException;

/**
 * Redis任务详细信息验证类
 * Class RedisTaskInfoValidate
 */
class RedisTaskInfoValidate
{
	/**
	 * 任务详细信息完整性验证
	 * @param $task_info
	 */
	public static function checkTaskInfo($task_info)
	{
		//task详细信息中所有数据的key
		$task_keys = [C::$TASK_KEY_ID, C::$TASK_KEY_AREA, C::$TASK_KEY_RESOURCE, C::$TASK_KEY_FREQUENCY,
			C::$TASK_KEY_INTERVAL, C::$TASK_KEY_MAX_NUM, C::$TASK_KEY_CUR_NUM, C::$TASK_KEY_ADVANCE_CONFIG,
			C::$TASK_KEY_RESOURCE, C::$TASK_KEY_CUSTOM_DEVICE];

		foreach ($task_keys as $value) {
			if (!isset($task_info[$value])) {
				throw new TaskInfoException('Task Info Missing key:' . $value, 3312);
			}
		}
	}
}