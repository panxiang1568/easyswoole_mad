<?php

namespace App\model;

use Exception;
use ryan\tools\Ipip;

/**
 * Class IP
 * @author Ryan <43352901@qq.com>
 * @date 2019-09-19 15:58
 * @package App\model
 */
class IP
{
    /**
     * 解析IP数据
     * @param string|null $ip
     * @return array [大陆,国家,区域,城市]
     */
    public static function parse(?string $ip): array
    {
        try{
            $map = Ipip::getInstance()->findMap($ip, 'EN');
            //局域网IP、保留地址、回环地址、异常等，将区域信息设置为空字符
	        if (empty($map['continent_code'])) {
		        $result = ['','','',''];
	        } else {
		        $result = [
			        $map['continent_code'],
			        $map['country_code'],
			        $map['region_name'],
			        $map['city_name'],
		        ];
	        }

        } catch ( Exception $e) {
            $result = ['','','',''];
        }

        return $result;
    }
}