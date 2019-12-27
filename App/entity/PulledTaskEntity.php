<?php

namespace App\entity;
/**
 * 设备已拉取任务实体类
 * Class PulledTaskEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:22
 * @package App\entity
 */
class PulledTaskEntity
{

    /**
     * @var string 首次拉取时间
     */
    private $first_pull_time;
    /**
     * @var string 最后一次拉取时间
     */
    private $last_pull_time;

    /**
     * @return mixed
     */
    public function getFirstPullTime()
    {
        return $this->first_pull_time;
    }

    /**
     * @param mixed $first_pull_time
     */
    public function setFirstPullTime($first_pull_time): void
    {
        $this->first_pull_time = $first_pull_time;
    }

    /**
     * @return mixed
     */
    public function getLastPullTime()
    {
        return $this->last_pull_time;
    }

    /**
     * @param mixed $last_pull_time
     */
    public function setLastPullTime($last_pull_time): void
    {
        $this->last_pull_time = $last_pull_time;
    }

}