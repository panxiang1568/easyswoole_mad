<?php

namespace App\logic\resource;
use App\constant\C;

class Link implements IResource
{

	/**
	 * 封装链接类型资源数据
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme, $url_domain = ''): array
    {
	    $response_task[ C::$KEY_OBJECT_URI ] = $resource_info[ C::$RSC_KEY_OBJECT_URI ];

	    if ( $scheme == 'https' ) {
		    $response_task[ C::$KEY_OBJECT_URI ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_OBJECT_URI ] );
	    }

	    return $response_task;
    }
}