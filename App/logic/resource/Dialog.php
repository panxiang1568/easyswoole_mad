<?php

namespace App\logic\resource;
use App\constant\C;

class Dialog implements IResource
{

	/**
	 * 封装弹框类型资源数据
	 * @param $response_task
	 * @param $resource_info
	 * @param $url_domain
	 * @param $scheme
	 *
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme, $url_domain): array
    {
	    $response_task[ C::$KEY_BRIEF ]   = $resource_info[ C::$RSC_KEY_BRIEF ] ?? '';
	    $response_task[ C::$KEY_TITLE ]   = $resource_info[ C::$RSC_KEY_TITLE ];
	    $response_task[ C::$KEY_APPNAME ] = $resource_info[ C::$RSC_KEY_APPNAME ] ?? '';
	    $response_task[ C::$KEY_ACTION ]  = $resource_info[ C::$RSC_KEY_TYPE_ACTION ];
	    $response_task[ C::$KEY_ICON ]    = $url_domain . $resource_info[ C::$RSC_KEY_ICON ];

	    if ( $response_task[ C::$KEY_ACTION ] == '01' ) {

		    $response_task[ C::$KEY_OBJECT_URI ] =
			    htmlspecialchars_decode( $resource_info[ C::$RSC_KEY_OBJECT_URI ] );
	    } else {

		    $response_task[ C::$KEY_OBJECT_SIZE ]  = $resource_info[ C::$RSC_KEY_OBJECT_SIZE ];
		    $response_task[ C::$KEY_VERSION ]      = $resource_info[ C::$RSC_KEY_VERSION ];
		    $response_task[ C::$KEY_VERSION_CODE ] = $resource_info[ C::$RSC_KEY_VERSION_CODE ];
		    $response_task[ C::$KEY_PKGNAME ]      = $resource_info[ C::$RSC_KEY_PKGNAME ];
		    $response_task[ C::$KEY_OBJECT_URI ]   =
			    $url_domain . htmlspecialchars_decode( $resource_info[ C::$RSC_KEY_OBJECT_URI ] );

		    if ( $resource_info[ C::$RSC_KEY_START_TYPE ] == 1) {
			    $response_task[ C::$KEY_START ] = $resource_info[C::$RSC_KEY_START];
		    }

	    }

	    if ( $scheme == 'https' ) {
		    $response_task[ C::$KEY_OBJECT_URI ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_OBJECT_URI ] );

		    $response_task[ C::$KEY_ICON ] = str_replace( 'http://',
			    'https://', $response_task[ C::$KEY_ICON ] );
	    }

	    return  $response_task;
    }
}