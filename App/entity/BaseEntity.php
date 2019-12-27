<?php

namespace App\entity;

use App\exception\DataException;
use App\utils\Map;

/**
 * 任务检测和任务上报实体类基类
 * Class BaseEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:13
 * @package App\entity
 */
class BaseEntity
{

    /**
     * @var bool 请求信息base64编码状态
     */
    private $isBase64 = false;
    /**
     * @var bool 请求信息转码状态
     */
    private $isMapData = false;

    /**
     * @var string 服务端反馈明细
     */
    private $msg = '';


    /**
     * 数据编码
     * @param array|null $data 处理数据
     * @return false|string 数据编码结果
     */
    public function encode(?array $data)
    {
        /**
         * 数据转码
         */
        if ($this->isMapData) {
            $data = Map::replace_response($data);
        }

        /**
         * json转换
         */
        $response = $data ? json_encode($data, true, 320) : null;

        /**
         * Base64编码
         */
        if ($this->isBase64) {
            $response = base64_encode($response);
        }

        return $response;
    }

    /**
     * @param string|null $serialized
     * @return array|mixed
     */
    public function decode(?string $serialized)
    {
        /**
         * 删除首尾空格以及换行符
         */
        $body = trim($serialized);
        /**
         * 非JSON格式时，以Base64解码
         */
        if (0 !== strpos($body, '{')) {
            $body = base64_decode($body, true);
            $this->isBase64 = true;
        }
        /**
         * 解析Json
         */
        $body = json_decode($body, true, 320);
        /**
         * 验证解析数据是否为空
         */
        if (!is_array($body) || empty($body)) {
            throw new DataException("invalid request data");
        }

        /**
         * 判断当前数据是否需要转码
         */
        $this->isMapData = isset($body['version']) ? false : true;

        /**
         * 数据转码
         */
        if ($this->isMapData) {
            $body = Map::replace($body, 'request');
        }

        return empty($body) ? [] : $body;

    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(?string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return bool
     */
    public function isBase64(): bool
    {
        return $this->isBase64;
    }

    /**
     * @param bool $isBase64
     */
    public function setIsBase64(bool $isBase64): void
    {
        $this->isBase64 = $isBase64;
    }

    /**
     * @return bool
     */
    public function isMapData(): bool
    {
        return $this->isMapData;
    }

    /**
     * @param bool $isMapData
     */
    public function setIsMapData(bool $isMapData): void
    {
        $this->isMapData = $isMapData;
    }



}