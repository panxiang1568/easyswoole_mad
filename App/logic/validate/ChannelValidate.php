<?php

namespace App\logic\validate;

use App\entity\IBaseInformation;
use App\exception\ValidateException;

/**
 * 渠道验证类
 * Class ChannelValidate
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 16:23
 * @package App\logic\validate
 */
class ChannelValidate
{

    /**
     * 验证渠道信息是否可用（可以运营 or 等待测试）
     * @param IBaseInformation $obj 请求实例
     */
    public static function isUsable(IBaseInformation $obj)
    {
        //判断渠道信息是否处于"不可运营"状态
        $validateResult = true;
        if (!$validateResult) {
            throw new ValidateException("invalid channel or channel is not available", 2001, $obj);
        }
    }
}