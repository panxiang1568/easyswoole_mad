<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class UpgradeValidate implements ITaskValidate
{

    /**
     * 判断是否是自升级任务，并满足自升级条件
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {
        $resource = $taskEntity->getResource();

        if($resource == C::$RESOURCE_UPGRADE){

            $resource_info = $taskEntity->getResourceInfo();
            $stub_version = $resource_info[C::$TASK_UPGRADE_STUB] ?? '';
            $impl_version = $resource_info[C::$TASK_UPGRADE_IMPL] ?? '';

            //设备未设置impl或者stub版本，返回false
            if(!$stub_version || !$impl_version) return false;

            $req_stub = $appRequestEntity->getStubVersion();
            $req_impl = $appRequestEntity->getImpl();

            //如果设备的impl版本 >= 任务设置的impl版本，返回false
            if(in_array(version_compare($req_impl, $impl_version), [0,1])) return false;
            //如果任务的stub版本为4.0.10, 设备的stub版本 > 4.0.10 ,返回false
            if($stub_version == C::$TASK_STUB_1 && (version_compare($req_stub , C::$STUB_4_0_10) > 0)) return false;
            //如果任务的stub版本为4.0.12, 设备的stub版本 != 4.0.12 ,返回false
            if($stub_version == C::$TASK_STUB_2 && (version_compare($req_stub , C::$STUB_4_0_12) !== 0)) return false;
            //如果任务的stub版本为4.0.14, 设备的stub版本 != 4.0.14 或者 设备的stub版本 != 4.0.13,返回false
            if($stub_version == C::$TASK_STUB_3 && ((version_compare($req_stub , C::$STUB_4_0_14) !== 0) && (version_compare($req_stub , C::$STUB_4_0_13) !== 0))) return false;
            //如果任务的stub版本为4.2.XX, 设备的stub版本 < 4.2.30 ,返回false
            if($stub_version == C::$TASK_STUB_4 && (version_compare($req_stub , C::$STUB_4_2_30) < 0)) return false;

        }

        return true;
    }
}