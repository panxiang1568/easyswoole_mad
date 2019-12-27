<?php

namespace App\exception;

use App\entity\AppResponseEntity;
use App\constant\C;
use RuntimeException;

/**
 * 请求数据异常
 * Class DataException
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:22
 * @package App\exception
 */
class DataException extends RuntimeException implements IException
{
    private $isBase64;
    private $isMapData;

    public function __construct($message = null, $code = 2000, $isBase64 = false, $isMapData = true)
    {
        parent::__construct($message, $code);
        $this->isBase64 = $isBase64;
        $this->isMapData = $isMapData;
    }
    /**
     * 获取输出结果
     * @return mixed
     */
    public function getResponse()
    {
        $entity = new AppResponseEntity();
        $entity->setVersion("1.0");
        $entity->setCode("1502");
	    $entity->setSession(C::$SESSION_APP_RSP);
        $entity->setIsBase64($this->isBase64);
        $entity->setIsMapData($this->isMapData);
        $entity->setMsg($this->getMessage());
        return $entity->serialize();
    }

}