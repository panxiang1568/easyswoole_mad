<?php


namespace App\entity;


/**
 * 任务检测和任务上报 基础接口
 * Interface IEntity
 * @package App\entity
 */
interface IEntity
{

    /**
     * 请求信息是否使用base64
     * @return bool
     */
    public function isBase64():bool;

    /**
     * 请求信息是否转码
     * @return bool
     */
    public function isMapData():bool;

    /**
     * 获取Version
     * @return string
     */
    public function getVersion(): string;

    /**
     * 获取Session
     * @return string
     */
    public function getSession(): string;

    /**
     * 序列化
     * @return string
     */
    public function serialize(): string;

    /**
     * 反序列化
     * @param $serialized
     */
    public function unserialize($serialized): void;

}