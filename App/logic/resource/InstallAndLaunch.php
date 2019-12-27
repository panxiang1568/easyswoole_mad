<?php

namespace App\logic\resource;
use App\constant\C;

class InstallAndLaunch implements IResource
{

	/**
	 * 封装安装并启动资源类型数据
	 *
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme, $url_domain): array
    {
	    $response_task[ C::$KEY_PKGNAME ]      = $resource_info[ C::$RSC_KEY_PKGNAME ];
	    $response_task[ C::$KEY_APPNAME ]      = $resource_info[ C::$RSC_KEY_APPNAME ];
	    $response_task[ C::$KEY_VERSION ]      = $resource_info[ C::$RSC_KEY_VERSION ];
	    $response_task[ C::$KEY_VERSION_CODE ] = $resource_info[ C::$RSC_KEY_VERSION_CODE ];
	    $response_task[ C::$KEY_OBJECT_SIZE ]  = $resource_info[ C::$RSC_KEY_OBJECT_SIZE ];
	    $response_task[ C::$KEY_BRIEF ]        = $resource_info[ C::$RSC_KEY_BRIEF ] ?? '';
	    $response_task[ C::$KEY_WIFI_ONLY ]    = $resource_info[ C::$RSC_KEY_WIFI_ONLY ] ? "true" : "false";
	    $response_task[ C::$KEY_START ]        = $resource_info[ C::$RSC_KEY_START ];
	    $response_task[ C::$KEY_OBJECT_URI ]   = $url_domain . $resource_info[ C::$RSC_KEY_OBJECT_URI ];
	    $response_task[ C::$KEY_ICON ]         = $url_domain . $resource_info[ C::$RSC_KEY_ICON ];

	    if ( $scheme == 'https' ) {
		    $response_task[ C::$KEY_OBJECT_URI ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_OBJECT_URI ] );

		    $response_task[ C::$KEY_ICON ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_ICON ] );
	    }

	    return  $response_task;
    }
}