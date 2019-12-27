<?php

namespace App\exception;

use App\constant\C;
use App\entity\AppResponseEntity;
use App\entity\IEntity;
use RuntimeException;

/**
 * 验证异常类
 * Class ValidateException
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:25
 * @package App\exception
 */
class ValidateException extends RuntimeException implements IException
{
    private $entity;

    public function __construct($message, $code, IEntity $obj)
    {
        parent::__construct($message, $code);
        $this->entity = $obj;
    }
    /**
     * 获取输出结果
     * @return mixed
     */
    public function getResponse()
    {
        $entity = new AppResponseEntity();
        $entity->setVersion($this->entity->getVersion());
        $entity->setSession($this->entity->getSession());
        $entity->setCycle($this->entity->getCycle());
        $entity->setCode(C::$CODE_1500);
        $entity->setIsBase64($this->entity->isBase64());
        $entity->setIsMapData($this->entity->isMapData());
        $entity->setMsg($this->getMessage());
        return $entity->serialize();
    }

}