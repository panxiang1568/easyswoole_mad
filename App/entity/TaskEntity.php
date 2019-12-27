<?php
namespace App\entity;
use App\constant\C;

/**
 * 任务实体类
 * Class TaskEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:22
 * @package App\entity
 */
class TaskEntity
{

    /**
     * 表属性
     */
    private $id;
    private $area;
    private $resource;
    private $frequency;
    private $interval;
    private $max_num;
    private $cur_num;
    private $advance_config;
    private $resource_info;
    private $custom_device;

    //设备拉取任务信息
    private $task_pull_time;
    private $task_pull_num;
    private $isPull;

    //高级配置项
    private $country_rule;
    private $country;
    private $region;
    private $city;
    private $model_rule;
    private $model;
    private $group;


    /**
     * 序列化
     * @return array
     */
    public function serialize(): array
    {
        $data                                = [];
	    $data[ C::$TASK_KEY_ID ]             = $this->getId();
	    $data[ C::$TASK_KEY_AREA ]           = $this->getArea();
	    $data[ C::$TASK_KEY_RESOURCE ]       = $this->getResource();
	    $data[ C::$TASK_KEY_FREQUENCY ]      = $this->getFrequency();
	    $data[ C::$TASK_KEY_INTERVAL ]       = $this->getInterval();
	    $data[ C::$TASK_KEY_MAX_NUM ]        = $this->getMaxNum();
	    $data[ C::$TASK_KEY_CUR_NUM ]        = $this->getCurNum();
	    $data[ C::$TASK_KEY_ADVANCE_CONFIG ] = $this->getAdvanceConfig();
	    $data[ C::$TASK_KEY_RESOURCE_INFO ]   = $this->getResourceInfo();
	    $data[ C::$TASK_KEY_CUSTOM_DEVICE ]   = $this->getCustomDevice();

        return $data;
    }


    /**
     * 反序列化
     * @param array|null $serialized
     */
    public function unserialize(?array $serialized): void
    {
        //如果有数据异常，直接在从redis获取数据时验证捕获
	    $this->setId( $serialized[ C::$TASK_KEY_ID ] );
	    $this->setArea( $serialized[ C::$TASK_KEY_AREA ] );
	    $this->setResource( $serialized[ C::$TASK_KEY_RESOURCE ] );
	    $this->setFrequency( $serialized[ C::$TASK_KEY_FREQUENCY ] );
	    $this->setInterval( $serialized[ C::$TASK_KEY_INTERVAL ] );
	    $this->setMaxNum( $serialized[ C::$TASK_KEY_MAX_NUM ] );
	    $this->setCurNum( $serialized[ C::$TASK_KEY_CUR_NUM ] );
	    $this->setAdvanceConfig( $serialized[ C::$TASK_KEY_ADVANCE_CONFIG ] );
	    $this->setResourceInfo( $serialized[ C::$TASK_KEY_RESOURCE_INFO ] );
	    $this->setCustomDevice( $serialized[ C::$TASK_KEY_CUSTOM_DEVICE ] );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $interval
     */
    public function setInterval($interval): void
    {
        $this->interval = $interval ? explode(',', $interval) : [] ;
    }

    /**
     * @return mixed
     */
    public function getMaxNum()
    {
        return $this->max_num;
    }

    /**
     * @param mixed $max_num
     */
    public function setMaxNum($max_num): void
    {
        $this->max_num = $max_num;
    }

    /**
     * @return mixed
     */
    public function getCurNum()
    {
        return $this->cur_num;
    }

    /**
     * @param mixed $cur_num
     */
    public function setCurNum($cur_num): void
    {
        $this->cur_num = $cur_num;
    }

    /**
     * @return mixed
     */
    public function getAdvanceConfig()
    {
        return $this->advance_config;
    }

    /**
     * @param mixed $advance_config
     */
    public function setAdvanceConfig($advance_config): void
    {
        $this->advance_config = $advance_config ? json_decode($advance_config, true) : [];

        $this->setCountryRule($this->advance_config[ C::$TASK_KEY_ADVANCE_COUNTRY_RULE] ?? '');
        $this->setCountry($this->advance_config[ C::$TASK_KEY_ADVANCE_COUNTRY] ?? '');
        $this->setRegion($this->advance_config[ C::$TASK_KEY_ADVANCE_REGION] ?? '');
        $this->setCity($this->advance_config[ C::$TASK_KEY_ADVANCE_CITY] ?? '');
        $this->setModelRule($this->advance_config[ C::$TASK_KEY_ADVANCE_MODEL_RULE] ?? '');
        $this->setModel($this->advance_config[ C::$TASK_KEY_ADVANCE_MODEL] ?? '');
        $this->setGroup($this->advance_config[ C::$TASK_KEY_ADVANCE_GROUP] ?? '');
    }

    /**
     * @return mixed
     */
    public function getResourceInfo()
    {
        return $this->resource_info;
    }

    /**
     * @param mixed resource_info
     */
    public function setResourceInfo($resource_info): void
    {
        $this->resource_info = $resource_info ? json_decode($resource_info, true) : [];
    }

    /**
     * @return mixed
     */
    public function getCustomDevice()
    {
        return $this->custom_device;
    }

    /**
     * @param mixed $custom_device
     */
    public function setCustomDevice($custom_device): void
    {
        $this->custom_device = $custom_device;
    }

    /**
     * @return mixed
     */
    public function getTaskPullTime()
    {
        return $this->task_pull_time;
    }

    /**
     * @param mixed $task_pull_time
     */
    public function setTaskPullTime($task_pull_time): void
    {
        $this->task_pull_time = $task_pull_time;
    }

    /**
     * @return mixed
     */
    public function getTaskPullNum()
    {
        return $this->task_pull_num;
    }

    /**
     * @param mixed $task_pull_num
     */
    public function setTaskPullNum($task_pull_num): void
    {
        $this->task_pull_num = $task_pull_num;
    }

    /**
     * @return mixed
     */
    public function getIsPull()
    {
        return $this->isPull;
    }

    /**
     * @param mixed $is_pull
     */
    public function setIsPull($is_pull): void
    {
        $this->isPull = $is_pull;
    }

    /**
     * @return mixed
     */
    public function getCountryRule()
    {
        return $this->country_rule;
    }

    /**
     * @param $country_rule
     */
    public function setCountryRule($country_rule): void
    {
        $this->country_rule = $country_rule;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country ? explode(',', $country) : [];
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param $region
     */
    public function setRegion(?string $region): void
    {
        $this->region = $region ? explode(',', $region) : [];
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city ? explode(',', $city) : [];

        if($this->city){

            $citys = [];

            foreach ($this->city as $task_city){

                $task_city = explode('-', $task_city);
                $citys[$task_city[0]][] = $task_city[1];

            }

            $this->city = $citys;
        }

    }

    /**
     * @return mixed
     */
    public function getModelRule()
    {
        return $this->model_rule;
    }

    /**
     * @param $model_rule
     */
    public function setModelRule($model_rule): void
    {
        $this->model_rule = $model_rule;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $model
     */
    public function setModel(?string $model): void
    {
        $this->model = $model ? explode(',', $model) : [];
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $group
     */
    public function setGroup(?string $group): void
    {
        $this->group = $group ? explode(',', $group) : [];
    }

}