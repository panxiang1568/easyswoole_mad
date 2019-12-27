<?php

namespace App\logic\validate\task;

use App\constant\C;
use App\entity\AppRequestEntity;
use App\entity\TaskEntity;

class AreaAdvancedValidate implements ITaskValidate
{

    /**
     * 检验地域信息
     * @param AppRequestEntity $appRequestEntity
     * @param TaskEntity $taskEntity
     * @return bool
     */
    public static function run(AppRequestEntity $appRequestEntity, TaskEntity $taskEntity):bool
    {
        $country = $appRequestEntity->getCountry();
        $region = $appRequestEntity->getRegion();
        $city = $appRequestEntity->getCity();
        $task_city = $taskEntity->getCity();
        $country_rule = $taskEntity->getCountryRule();
        $task_country = $taskEntity->getCountry();
        $task_region = $taskEntity->getRegion();

        switch ($country_rule){
            case C::$TASK_WHITE:
                //国家验证
                if($task_country && !in_array($country, $task_country)) return false;
                //省份验证
                if($task_region && !in_array($region, $task_region)) return false;
                //城市验证
                if($task_city && in_array($region, array_keys($task_city)) && !in_array($city, $task_city[$region])) return false;
                break;
            case C::$TASK_BLACK:
                if($task_country && in_array($country, $task_country)){
                    //国家验证
                    if($country != C::$CHINA_CODE) return false;
                    if($country == C::$CHINA_CODE && !$task_region && !$task_city) return false;
                    //省份验证
                    if($task_region && in_array($region, $task_region) && !in_array($region, array_keys($task_city))) return false;
                    //城市验证
                    if($task_region && $task_city && in_array($region, $task_region) && in_array($region, array_keys($task_city)) && in_array($city, $task_city[$region])) return false;
                }
                break;
            default:
                break;
        }

        return true;
    }

}