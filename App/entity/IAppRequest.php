<?php


namespace App\entity;

/**
 * 任务检测信息
 * Interface IAppRequest
 * @package App\entity
 */
interface IAppRequest extends IBaseInformation
{

    /**
     * 是否包含普通任务
     * @return bool
     */
    public function getIsContainGeneral(): bool;

    /**
     * 是否是测试设备
     * @return bool
     */
    public function getIsTest(): bool;

    /**
     * 获取Impl版本
     * @return string
     */
    public function getImpl(): string;

    /**
     * 获取Stub版本
     * @return string
     */
    public function getStubVersion(): string;

    /**
     * 获取设备执行能力
     * @return array
     */
    public function getCapability(): array;

    /**
     * 获取设备所在国家
     * @return string
     */
    public function getCountry(): string;

    /**
     * 获取设备所在大洲或省份
     * @return string
     */
    public function getRegion(): string;

    /**
     * 获取设备所在城市
     * @return string
     */
    public function getCity(): string;

    /**
     * 获取设备ID（服务端生成ID）
     * @return string
     */
    public function getDeviceId(): string;

    /**
     * 获取设备注册时间
     * @return string
     */
    public function getRegisterTime(): string;

    /**
     * 获取设备静默期
     * @return int
     */
    public function getSilent(): int;

    /**
     * 获取设备渠道ID
     * @return int
     */
    public function getChannelId(): int;

}