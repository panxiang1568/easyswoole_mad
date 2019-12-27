<?php

namespace App\logic\filter;

use App\entity\AppRequestEntity;
use App\exception\TaskInfoException;
use App\exception\UpgradeException;
use App\model\Task;
use App\utils\Log;
use ReflectionClass;
use ReflectionException;

class TaskAdvancedFilter
{

    /**
     * 任务校验类集合，用于高级规则过滤反射机制
     * @var array
     */
    public static $advancedClass = [
        //Filter 1：返回集合中有且只有一个普通任务
        '\App\logic\validate\task\GeneralFrequencyValidate',
        //Filter 2：校验任务地域性
        '\App\logic\validate\task\AreaValidate',
        //Filter 3：校验设备执行能力
        '\App\logic\validate\task\CapabilityValidate',
        //Filter 4：校验任务类型及发布量
        '\App\logic\validate\task\TypeAndReleaseValidate',
        //Filter 5：校验自升级
        '\App\logic\validate\task\UpgradeValidate',
        //Filter 6：校验地域高级规则
        '\App\logic\validate\task\AreaAdvancedValidate',
        //Filter 7：校验机型名称
        '\App\logic\validate\task\ModelValidate',
        //Filter 8：校验设备分组或自定义设备
        '\App\logic\validate\task\DeviceValidate',
        //Filter 9：校验触达量（包含触达量递增及任务拉取时间更新），放在最后一步
        '\App\logic\validate\task\TouchValidate'
    ];

    /**
     * 任务高级规则过滤
     * @param AppRequestEntity $appRequestEntity 请求实例
     * @return array 过滤后的任务实体集合
     */
    public static function run(AppRequestEntity $appRequestEntity): array
    {
        $result = [];
        $task_id_arr = [];
        /**
         * 遍历任务ID集合，高级规则匹配过滤
         */
        foreach ($appRequestEntity->getTaskIds() as $taskId) {

            $taskEntity = null;

            try {

                /**
                 * 获取当前任务
                 */
                $taskEntity = Task::get( $taskId );
                $isIgnore = false;
                foreach (TaskAdvancedFilter::$advancedClass as $advancedClass) {
                    $validate = new ReflectionClass($advancedClass);
                    $method = $validate->getMethod("run");
                    $validateResult = $method->invoke($validate, $appRequestEntity, $taskEntity);
                    if (!$validateResult) {
                        $isIgnore = true;
                        break;
                    }
                }
                if($isIgnore) continue;
            } catch (TaskInfoException $e) {
                //任务详细信息异常类，某任务信息不完整直接跳过
                /**
                 * 写入日志文件
                 */
                Log::write("./Log/task_info_error.log", $e->getMessage().':'.$e->getCode() . "\n");
                continue;
            } catch (UpgradeException $e) {
                //触发自升级任务，强制跳出
                $result = [$taskEntity];
                //存储所有可拉取任务ID
                $task_id_arr = [$taskId];
                //将所有可拉取到的任务ID写入日志
                $appRequestEntity->setLogTaskIds($task_id_arr);
                break;
            } catch (ReflectionException $e) {

                //反射验证类异常时，认为过滤失败
                //记录异常日志，跟踪异常问题
                /**
                 * 写入日志文件
                 */
                Log::write("./Log/task_error.log", $e->getTraceAsString() . "\n");
                continue;
            }

            /**
             * 将任务ID追加过滤后的结果集
             */
            $result[] = $taskEntity;
            //存储所有可拉取任务ID
            $task_id_arr[] = $taskId;

        }
        //将所有可拉取到的任务ID写入日志
        $appRequestEntity->setLogTaskIds($task_id_arr);
        
        return $result;
    }
}