<?php

namespace App\model;

/**
 * Class ApplicationActive
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 15:58
 * @package App\model
 */
class ApplicationActive
{

    use CommonTools;

    /**
     * 获取应用保活信息
     * @return array
     */
    public static function get(): array
    {

        $applicationArr = [];
        $redis = self::getRedis();

        //获取应用保活信息
        $applicationActive = $redis->hGetAll('application_active');

        if($applicationActive){
            foreach ($applicationActive as $active){
                $applicationArr[] = json_decode($active, true);
            }
        }

        return $applicationArr;
    }


}