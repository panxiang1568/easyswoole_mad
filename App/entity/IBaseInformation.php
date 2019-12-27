<?php


namespace App\entity;

/**
 * 基础信息
 * Interface IBaseInformation
 * @package App\entity
 */
interface IBaseInformation extends IEntity
{

    /**
     * 获取App key
     * @return string
     */
    public function getAppid(): string;

    /**
     * 获取渠道
     * @return string
     */
    public function getChannel(): string;

    /**
     * 获取品牌
     * @return string
     */
    public function getMan(): string;

    /**
     * 获取机型
     * @return string
     */
    public function getMod(): string;

    /**
     * 获取设备号
     * @return string
     */
    public function getDevid(): string;


}