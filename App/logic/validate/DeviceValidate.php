<?php

namespace App\logic\validate;

use App\entity\IAppRequest;
use App\exception\ValidateException;
use App\model\Device;
use Throwable;

/**
 * 设备验证类
 * Class DeviceValidate
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 17:14
 * @package App\logic\validate
 */
class DeviceValidate
{
	/**
	 * 验证设备是否是测试设备
	 *
	 * @param IAppRequest $obj 请求实体
	 *
	 * @return bool True:测试设备，False：非测试设别
	 * @throws Throwable
	 */
    public static function isTest(IAppRequest $obj): bool
    {
        return Device::isTest($obj);
    }

	/**
	 * 验证设备是否处于静默期范围内
	 *
	 * @param IAppRequest $obj 请求实体
	 */
    public static function isSilent(IAppRequest $obj): void
    {
        //获取设备的静默期，判断当前设备是否处于静默期范围内么，注册时间+静默期 < 当前时间

        $silent = $obj->getSilent();

        //注册时间格式是时间戳
        $isSilent = floor((time() - $obj->getRegisterTime())/86400) < $silent;
        
        if ($isSilent) {
            throw new ValidateException("device silent", 2002, $obj);
        }

    }
}