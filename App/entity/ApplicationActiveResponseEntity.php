<?php

namespace App\entity;

/**
 * 应用保活响应实体类
 * Class ApplicationActiveResponseEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:13
 * @package App\entity
 */
class ApplicationActiveResponseEntity extends BaseEntity implements IEntity
{

    private $applicationActive;
    /**
     * 序列化
     * @return string
     */
    public function serialize(): string
    {
        $response = $this->getApplicationActive();

        return parent::encode($response);
    }


    /**
     * @return array
     */
    public function getApplicationActive(): ?array
    {
        return $this->applicationActive;
    }

    /**
     * @param array|null $applicationActive
     */
    public function setApplicationActive(?array $applicationActive): void
    {
        $this->applicationActive = $applicationActive ? $applicationActive : null;
    }


    /**
     * 获取Version
     * @return string
     */
    public function getVersion(): string
    {
        return '';
    }

    /**
     * 获取Session
     * @return string
     */
    public function getSession(): string
    {
        return '';
    }

    /**
     * 反序列化
     * @param $serialized
     */
    public function unserialize($serialized): void
    {

    }
}