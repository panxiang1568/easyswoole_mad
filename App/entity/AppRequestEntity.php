<?php

namespace App\entity;


use App\constant\C;
use App\exception\DataException;

/**
 * 任务检测请求实体类
 * Class AppRequestEntity
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-20 17:12
 * @package App\entity
 */
class AppRequestEntity extends BaseEntity implements IAppRequest
{
    /**
     * 请求信息
     */
    private $version;
    private $session;
    private $devid;
    private $utdid;
    private $man;
    private $mod;
    private $osv;
    private $lang;
    private $operator;
    private $imsi;
    private $dldir;
    private $avaisize;
    private $totalsize;
    private $mac;
    private $carrier;
    private $appid;
    private $pkgname;
    private $channel;
    private $impl;
    private $stub_version;
    private $capability;

    /**
     * 追加信息
     */
    //IP地址
    private $ip;
    //请求协议类型
	private $scheme;
    //大陆
    private $continent;
    //国家
    private $country;
    //区域（国外：大洲；国内：省份）
    private $region;
    //城市
    private $city;


    /**
     * 自定义属性
     */
    //设备唯一标识ID（服务器生成）
    private $deviceId;
    //设备注册时间
    private $registerTime;
    //是否是测试设备
    private $isTest;
    //是否包含普通任务
    private $isContainGeneral;
    //需检测的任务ID集合
    private $taskIds;
    //设备已拉取过的任务ID集合及拉取信息
    private $pulledTaskIds;
    //渠道ID
    private $channelId;
    //设备静默期
    private $silent;
    //设备所属分组ID集合
    private $groupIds;
    //设备为自定义设备，所属任务ID集合
    private $taskDeviceIds;
    //设备心跳周期
    private $cycle;
    //可拉取任务ID集合
    private $taskIdList;


    /**
     * 请求实例构造函数
     * 实例化时反序列化数据，并验证数据完整性
     * AppRequestEntity constructor.
     * @param $serialized
     */
    public function __construct($serialized)
    {
        $this->unserialize($serialized);
        $this->isEmpty();
    }

    /**
     * 验证必要字段
     * 前期规则简单，如规则复杂创建独立validate类
     * @return bool true 不为空
     */
    public function isEmpty()
    {
        if (empty($this->version)) {
            throw new DataException('invalid version');
        }
        if (empty($this->session)) {
            throw new DataException('invalid session');
        }
        if (empty($this->appid)) {
            throw new DataException('invalid product key');
        }
        if (empty($this->channel)) {
            throw new DataException('invalid channel');
        }
        return true;
    }


    /**
     * 序列化
     * @return string
     */
    public function serialize(): string
    {
        $data = [];
        $data[C::$KEY_VERSION] = $this->getVersion();
        $data[C::$KEY_SESSION] = $this->getSession();
        $this->getDevid() && $data[C::$KEY_DEV_ID] = $this->getDevid();
        $this->getUtdid() && $data[C::$KEY_UTD_ID] = $this->getUtdid();
        $this->getMan() && $data[C::$KEY_MAN] = $this->getMan();
        $this->getMod() && $data[C::$KEY_MOD] = $this->getMod();
        $this->getOsv() && $data[C::$KEY_OSV] = $this->getOsv();
        $this->getLang() && $data[C::$KEY_LANG] = $this->getLang();
        $this->getOperator() && $data[C::$KEY_OPERATOR] = $this->getOperator();
        $this->getImsi() && $data[C::$KEY_IMSI] = $this->getImsi();
        $this->getDldir() && $data[C::$KEY_DLDIR] = $this->getDldir();
        $this->getAvaisize() && $data[C::$KEY_AVAISIZE] = $this->getAvaisize();
        $this->getTotalsize() && $data[C::$KEY_TOTAL_SIZE] = $this->getTotalsize();
        $this->getMac() && $data[C::$KEY_MAC] = $this->getMac();
        $this->getCarrier() && $data[C::$KEY_CARRIER] = $this->getCarrier();
        $data[C::$KEY_IP] = $this->getIp();
        $data[C::$AREA_COUNTRY] = $this->getCountry();
        $data[C::$AREA_REGION] = $this->getRegion();
        $data[C::$AREA_CITY] = $this->getCity();
        $data[C::$TASK_ID] = $this->getLogTaskIds();

        return parent::encode($data);
    }


    /**
     * 反序列化
     * @param $serialized
     */
    public function unserialize($serialized): void
    {
        $body = parent::decode($serialized);

        $this->setVersion($body[C::$KEY_VERSION] ?? "");
        $this->setSession($body[C::$KEY_SESSION] ?? "");
        $this->setDevid( $body[C::$KEY_DEV_ID] ?? "");
        $this->setUtdid( $body[C::$KEY_UTD_ID] ?? "");
        $this->setMan($body[C::$KEY_MAN] ?? "");
        $this->setMod($body[C::$KEY_MOD] ?? "");
        $this->setOsv($body[C::$KEY_OSV] ?? "");
        $this->setLang($body[C::$KEY_LANG] ?? "");
        $this->setOperator($body[C::$KEY_OPERATOR] ?? "");
        $this->setImsi($body[C::$KEY_IMSI] ?? "");
        $this->setDldir($body[C::$KEY_DLDIR] ?? "");
        $this->setAvaisize($body[C::$KEY_AVAISIZE] ?? "");
        $this->setTotalsize( $body[C::$KEY_TOTAL_SIZE] ?? "");
        $this->setMac($body[C::$KEY_MAC] ?? "");
        $this->setCarrier($body[C::$KEY_CARRIER] ?? []);
        $this->setIp($body[C::$KEY_DEBUG_IP] ?? '');
    }

    /**
     * @return mixed
     */
    public function getVersion(): string
    {
        return $this->version;
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
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession(?string $session): void
    {
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    public function getDevid(): string
    {
        return $this->devid;
    }

    /**
     * @param mixed $devid
     */
    public function setDevid(?string $devid): void
    {
        $this->devid = $devid;
    }

    /**
     * @return mixed
     */
    public function getUtdid(): string
    {
        return $this->utdid;
    }

    /**
     * @param mixed $utdid
     */
    public function setUtdid(?string $utdid): void
    {
        $this->utdid = $utdid;
    }

    /**
     * @return mixed
     */
    public function getMan(): string
    {
        return $this->man;
    }

    /**
     * @param mixed $man
     */
    public function setMan(?string $man): void
    {
        $this->man = $man;
    }

    /**
     * @return mixed
     */
    public function getMod(): string
    {
        return $this->mod;
    }

    /**
     * @param mixed $mod
     */
    public function setMod(?string $mod): void
    {
        $this->mod = $mod;
    }

    /**
     * @return mixed
     */
    public function getOsv(): string
    {
        return $this->osv;
    }

    /**
     * @param mixed $osv
     */
    public function setOsv(?string $osv): void
    {
        $this->osv = $osv;
    }

    /**
     * @return mixed
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang(?string $lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator(?string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getImsi(): string
    {
        return $this->imsi;
    }

    /**
     * @param mixed $imsi
     */
    public function setImsi(?string $imsi): void
    {
        $this->imsi = $imsi;
    }

    /**
     * @return mixed
     */
    public function getDldir(): string
    {
        return $this->dldir;
    }

    /**
     * @param mixed $dldir
     */
    public function setDldir(?string $dldir): void
    {
        $this->dldir = $dldir;
    }

    /**
     * @return mixed
     */
    public function getAvaisize(): string
    {
        return $this->avaisize;
    }

    /**
     * @param mixed $avaisize
     */
    public function setAvaisize(?string $avaisize): void
    {
        $this->avaisize = $avaisize;
    }

    /**
     * @return mixed
     */
    public function getTotalsize(): string
    {
        return $this->totalsize;
    }

    /**
     * @param mixed $totalsize
     */
    public function setTotalsize(?string $totalsize): void
    {
        $this->totalsize = $totalsize;
    }

    /**
     * @return mixed
     */
    public function getMac(): string
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     */
    public function setMac(?string $mac): void
    {
        $this->mac = $mac;
    }

    /**
     * @return mixed
     */
    public function getCarrier(): array
    {
        return $this->carrier;
    }

    /**
     * @param array|null $carrier
     * @return void
     */
    public function setCarrier(?array $carrier): void
    {
        $this->carrier = $carrier;

        $this->setAPPID($carrier[C::$KEY_APPID] ?? "");
        $this->setPkgname($carrier[C::$KEY_PKGNAME] ?? "");
        $this->setChannel($carrier[C::$KEY_CHANNEL] ?? "");
        $this->setImpl($carrier[C::$KEY_IMPL] ?? "");
        $this->setStubVersion($carrier[C::$KEY_STUB_VERSION] ?? "");
        $this->setCapability($carrier[C::$KEY_CAPABILITY] ?? "01|02|03");
    }

    /**
     * @return mixed
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * @param mixed $appid
     */
    public function setAppid(?string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * @return mixed
     */
    public function getPkgname(): string
    {
        return $this->pkgname;
    }

    /**
     * @param mixed $pkgname
     */
    public function setPkgname(?string $pkgname): void
    {
        $this->pkgname = $pkgname;
    }

    /**
     * @return mixed
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getImpl(): string
    {
        return $this->impl;
    }

    /**
     * @param mixed $impl
     */
    public function setImpl(?string $impl): void
    {
        $this->impl = $impl;
    }

    /**
     * @return mixed
     */
    public function getStubVersion(): string
    {
        return $this->stub_version;
    }

    /**
     * @param mixed $stub_version
     */
    public function setStubVersion(?string $stub_version): void
    {
        $this->stub_version = $stub_version;
    }

    /**
     * @return mixed
     */
    public function getCapability(): array
    {
        return $this->capability ?? ['01', '02', '03'];
    }

    /**
     * @param mixed $capability
     */
    public function setCapability(?string $capability): void
    {
        $this->capability = explode("|", $capability);
    }

    /**
     * @return mixed
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

	/**
	 * @return string
	 */
    public function getScheme(): string
    {
    	return $this->scheme;
    }

	/**
	 * @param string|null $scheme
	 */
    public function setScheme(?string $scheme)
    {
    	$this->scheme = $scheme;
    }

    /**
     * @return mixed
     */
    public function getContinent(): string
    {
        return $this->continent;
    }

    /**
     * @param mixed $continent
     */
    public function setContinent(?string $continent): void
    {
        $this->continent = $continent;
    }

    /**
     * @return mixed
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    /**
     * @param mixed $deviceId
     */
    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return mixed
     */
    public function getRegisterTime(): string
    {
        return $this->registerTime;
    }

    /**
     * @param mixed $registerTime
     */
    public function setRegisterTime(?string $registerTime): void
    {
        $this->registerTime = $registerTime;
    }

    /**
     * @return mixed
     */
    public function getIsTest(): bool
    {
        return $this->isTest;
    }

    /**
     * @param mixed $isTest
     */
    public function setIsTest(?bool $isTest): void
    {
        $this->isTest = $isTest;
    }

    /**
     * @return mixed
     */
    public function getIsContainGeneral(): bool
    {
        return $this->isContainGeneral ?? false;
    }

    /**
     * @param mixed $isContainGeneral
     */
    public function setIsContainGeneral(?bool $isContainGeneral): void
    {
        $this->isContainGeneral = $isContainGeneral;
    }

    /**
     * @return mixed
     */
    public function getTaskIds(): array
    {
        return $this->taskIds ?? [];
    }

    /**
     * @param mixed $taskIds
     */
    public function setTaskIds(?array $taskIds): void
    {
        $this->taskIds = $taskIds;
    }

    /**
     * @return mixed
     */
    public function getPulledTaskIds():array
    {
        return $this->pulledTaskIds;
    }

    /**
     * @param mixed $pulledTaskIds
     */
    public function setPulledTaskIds($pulledTaskIds): void
    {
        $this->pulledTaskIds = $pulledTaskIds;
    }

    /**
     * @return mixed
     */
    public function getChannelId(): int
    {
        return $this->channelId;
    }

    /**
     * @param mixed $channelId
     */
    public function setChannelId(?int $channelId): void
    {
        $this->channelId = $channelId;
    }

    /**
     * @return mixed
     */
    public function getSilent(): int
    {
        return $this->silent;
    }

    /**
     * @param mixed $silent
     */
    public function setSilent(?int $silent): void
    {
        $this->silent = $silent;
    }

    /**
     * @return mixed
     */
    public function getGroupIds(): array
    {
        return $this->groupIds;
    }

    /**
     * @param string|null $groupIds
     */
    public function setGroupIds(?string $groupIds): void
    {
        $this->groupIds = $groupIds ? explode(',', $groupIds) : [];;
    }

    /**
     * @return mixed
     */
    public function getTaskDeviceIds(): array
    {
        return $this->taskDeviceIds;
    }

    /**
     * @param string|null $taskDeviceIds
     */
    public function setTaskDeviceIds(?string $taskDeviceIds): void
    {
        $this->taskDeviceIds = $taskDeviceIds ? explode(',', $taskDeviceIds) : [];;
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
	public function getLogTaskIds(): ?array
	{
		return $this->taskIdList ?? [];
	}

    /**
     * 将可拉取任务集合写入log
     * @param array|null $task_id_List
     */
    public function setLogTaskIds(?array $task_id_List): void
    {
        $this->taskIdList = $task_id_List;
    }

}