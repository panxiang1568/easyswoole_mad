<?php

namespace App\entity;

use App\constant\C;

/**
 * 任务检测响应实体类
 * Class AppResponseEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:13
 * @package App\entity
 */
class AppResponseEntity extends BaseEntity implements IEntity
{
    /**
     * 响应字段
     */
    private $version;
    private $session;
    private $code;
    private $cycle;
    private $applist;
    private $link;
    private $caplist;

    /**
     * 序列化
     * @return string
     */
    public function serialize(): string
    {
        $response = [
            C::$KEY_VERSION => $this->getVersion(),
            C::$KEY_CODE => $this->getCode(),
        ];

        $this->getSession() && $response[C::$KEY_SESSION] = $this->getSession();
        $this->getCycle() && $response[C::$KEY_CYCLE] = $this->getCycle();
        $this->getApplist() && $response[C::$KEY_APPLIST] = $this->getApplist();
        $this->getLink() && $response[C::$KEY_LINK] = $this->getLink();
        $this->getCaplist() && $response[C::$KEY_CAPLIST] = $this->getCaplist();
        $this->getMsg() && $response[C::$KEY_MSG] = $this->getMsg();

        return parent::encode($response);
    }

    /**
     * 反序列化
     * @param $serialized
     */
    public function unserialize($serialized): void
    {
        return;
    }

    /**
     * @return mixed
     */
    public function getVersion(): string
    {
        return $this->version ?? "1.0";
    }

    /**
     * @param mixed $version
     */
    public function setVersion(?string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getSession(): string
    {
        return $this->session ?? "";
    }

    /**
     * @param mixed $session
     */
    public function setSession(?string $session): void
    {

        $this->session = $session == C::$SESSION_APP_REQ ? C::$SESSION_APP_RSP : $session;
    }

    /**
     * @return mixed
     */
    public function getCode(): string
    {
        return $this->code ?? '1500';
    }

    /**
     * @param mixed $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCycle(): string
    {
        return $this->cycle ?? "";
    }

    /**
     * @param mixed $cycle
     */
    public function setCycle(?string $cycle): void
    {
        $this->cycle = $cycle;
    }

    /**
     * @return mixed
     */
    public function getApplist(): array
    {
        return $this->applist ?? [];
    }

    /**
     * @param mixed $applist
     */
    public function setApplist(?array $applist): void
    {
        $this->applist = $applist;
    }

    /**
     * @return mixed
     */
    public function getLink(): array
    {
        return $this->link ?? [];
    }

    /**
     * @param mixed $link
     */
    public function setLink(?array $link): void
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getCaplist(): array
    {
        return $this->caplist ?? [];
    }

    /**
     * @param mixed $caplist
     */
    public function setCaplist(?array $caplist): void
    {
        $this->caplist = $caplist;
    }


}