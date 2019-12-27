<?php

namespace App\logic\resource;
use App\constant\C;

class Launch implements IResource
{

	/**
	 * 封装启动资源类型数据
	 *
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme = '', $url_domain = ''): array
    {
	    $response_task[ C::$KEY_PKGNAME ]      = $resource_info[ C::$RSC_KEY_PKGNAME ];
	    $response_task[ C::$KEY_VERSION ]      = '1.0';
	    $response_task[ C::$KEY_VERSION_CODE ] = '1';
	    $response_task[ C::$KEY_START ]        = $resource_info[ C::$RSC_KEY_START ];

	    return $response_task;
    }
}