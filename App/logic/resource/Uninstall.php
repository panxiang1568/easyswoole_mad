<?php

namespace App\logic\resource;
use App\constant\C;

/**
 * 封装卸载资源类型数据
 * Class Uninstall
 * @package App\logic\resource
 */
class Uninstall implements IResource
{

	/**
	 * 封装卸载类型资源数据
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
	public static function run($response_task, $resource_info, $scheme = '', $url_domain = ''): array
    {
	    $response_task[ C::$KEY_PKGNAME ] = $resource_info[ C::$RSC_KEY_PKGNAME ];

	    return $response_task;
    }
}