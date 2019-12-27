<?php

namespace App\utils;

class Map
{

    public static $KEY = [
        'request' => [

            //通用字段
            "d0" => "version",
            "d1" => "session",

            //Session字段
            "s20" => "APP-REQ",
            "s22" => "NOTIFY-APP-REQ",
            "s24" => "EVENT-REQ",

            //任务检测 APP-REQ
            "d2" => "devid",
            "d3" => "utdid",
            "d4" => "man",
            "d5" => "mod",
            "d6" => "osv",
            "d8" => "lang",
            "d9" => "operator",
            //协议3.4以上删除log、msisdn、iccid字段
            "da" => "loc",
            "db" => "msisdn",
            "dc" => "iccid",
            "dd" => "imsi",
            "de" => "dldir",
            "df" => "avaisize",
            "dg" => "totalsize",
            "dh" => "mac",
            "dn" => "androidid",
            "c0" => "carrier",
            "c0.c1" => "appid",
            "c0.c2" => "pkgname",
            "c0.c3" => "channel",
            "c0.c4" => "version",
            "c0.c5" => "silent",
            "c0.c6" => "capability",
            "c0.c7" => "stub_version",


            //状态上报
            "r0" => "status",

            "r0.r1" => "code",
            "r0.r3" => "subcode",
            "r0.a1" => "correlator",
            "r0.a2" => "taskid",
            "r0.c1" => "appid",
            "r0.c3" => "channel",
            "r0.d4" => "man",
            "r0.d5" => "mod",


//---------------


            "a3" => "pkgname",
            "a4" => "appname",
            "a5" => "version",


            "au" => "rm",
            "ai" => "ins",
            "ts" => "ts",
            "a6" => "brief",
            "a7" => "objecturi",
            "a8" => "objectsize",
            "a9" => "icon",
            "a10" => "start",
            "a11" => "type",
            "a12" => "action",
            "a13" => "class",
            "a14" => "extra",
            "a15" => "operation",
            "a16" => "pic",
            "a17" => "strategy",
            "a18" => "targetapps",
            "a19" => "servicepkg",
            "a20" => "versionCode",
            "a22" => "title",
            "a23" => "md5",
            "a24" => "wifionly",
            "r2" => "cycle",
            "l0" => "link",
            "a0" => "applist",
            "a21" => "caplist",
            "s21" => "APP-RSP",
            "s23" => "NOTIFY-APP-RSP",
            "s31" => "APP-SELFUPDATE-RSP",

            "di" => "universal_udid",
            "dj" => "resistwipe_udid",
            "dk" => "universal_uuid",
            "dl" => "resistwipe_uuid",
            "dm" => "resistwipe_path",

        ],
        "response" => [

            "correlator"  => "a1",
            "taskid"      => "a2",
            "pkgname"     => "a3",
            "appname"     => "a4",
            "version"     => "a5",
            "brief"       => "a6",
            "objecturi"   => "a7",
            "objectsize"  => "a8",
            "icon"        => "a9",
            "start"       => "a10",
            "type"        => "a11",
            "action"      => "a12",
            "class"       => "a13",
            "extra"       => "a14",
            "operation"   => "a15",
            "pic"         => "a16",
            "strategy"    => "a17",
            "targetapps"  => "a18",
            "servicepkg"  => "a19",
            "versionCode" => "a20",
            "title"       => "a22",
            "md5"         => "a23",
            "wifionly"    => "a24",
            "session"            => "d1",
            "code"               => "r1",
            "cycle"              => "r2",
            "link"               => "l0",
            "correlator"         => "a1",
            "taskid"             => "a2",
            "operation"          => "a15",
            "objecturi"          => "a7",
            "applist"            => "a0",
            "caplist"            => "a21",
            "APP-RSP"            => "s21",
            "NOTIFY-APP-RSP"     => "s23",
            "APP-SELFUPDATE-RSP" => "s31",


            //通用字段
            "carrier" => "c0",
            "carrier.appid" => "c1",
            "channel" => "c3",
            "silent" => "c5",
            "capability" => "c6",
            "stub_version" => "c7",
        ]
    ];

    /**
     * 数据转码
     * @param $data array 转码数据
     * @param string $type request:请求数据转码，response:响应数据转码
     * @return array 转码后数据
     */
    public static function replace($data, $type = "request", $parentKey = "")
    {
        $result = [];
        foreach ($data as $k => $v) {
            /**
             * 为了避免父子级存在重复key，而映射表只有一维所导致无法映射的问题
             * 增加key父级前缀
             */
            if (is_numeric($k)) {
                $key = $parentKey;
            } else {
                $key = $parentKey ? $parentKey . "." . $k : $k;
            }

            /**
             * 1. Key不匹配时，不做处理(保留原始key及value)
             * 2. Key映射匹配且Value为数组时，以Key的映射值为新KEY，Value继续迭代
             * 3. Key映射匹配且Value非数组时，以Key的映射值为新KEY，以Value的映射值为新Value（Value映射失败则按原值输出）
             */
            if (!is_numeric($k) && !array_key_exists($key, Map::$KEY[$type])) {
                $result[$k] = $v;
            } else if (is_array($v)) {
                $result[Map::$KEY[$type][$key]] = Map::replace($v, $type, $key);
            } else {
                $result[Map::$KEY[$type][$key]] = array_key_exists($v, Map::$KEY[$type]) ? Map::$KEY[$type][$v] : $v;
            }
        }
        return $result;
    }

    /**
     * response数据转码
     * @param $data
     * @param string $type
     * @param string $parentKey
     * @return array
     */
    public static function replace_response($data, $type = "response", $parentKey = "")
    {
        $result = [];
        foreach ($data as $k => $v) {

            if (is_array($v)) {
                if(is_numeric($k)){
                    $result[] = Map::replace_response($v, $type, $parentKey);
                }else{
                    $result[Map::$KEY[$type][$k]] = Map::replace_response($v, $type, $k);
                }
            } else {
                if (!is_numeric($k) && !array_key_exists($k, Map::$KEY[$type])) {
                    $result[$k] = $v;
                } else {
                    $result[Map::$KEY[$type][$k]] = $v;
                }
            }

        }
        return $result;
    }

}