<?php
namespace App\logic\resource;
/**
 * 资源封装接口
 * Interface IResource
 * @package App\logic\resource
 */
interface IResource
{
	/**
	 * @param $response_task //下发给客户端的数据结构
	 * @param $resource_info //单个任务的resource_info信息
	 * @param $scheme //请求协议类型(HTTP/HTTPS)
	 * @param $url_domain //链接域名
	 * @return array
	 */
    public static function run($response_task, $resource_info, $scheme, $url_domain): array ;
}