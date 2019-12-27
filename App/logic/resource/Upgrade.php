<?php

namespace App\logic\resource;
use App\constant\C;

class Upgrade implements IResource
{

	/**
	 * 封装自升级类型资源数据
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme, $url_domain): array
    {
	    $response_task[ C::$KEY_VERSION ]     = $resource_info[ C::$RSC_KEY_IMPL ];
	    $response_task[ C::$KEY_OBJECT_SIZE ] = $resource_info[ C::$RSC_KEY_OBJECT_SIZE ];
	    $response_task[ C::$KEY_OBJECT_URI ]  =
		    $url_domain . htmlspecialchars_decode($resource_info[ C::$RSC_KEY_OBJECT_URI ]);

	    if ( $scheme == 'https' ) {
		    $response_task[ C::$KEY_OBJECT_URI ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_OBJECT_URI ] );
	    }

	    return $response_task;
    }
}