<?php

namespace ryan\tools;

use ryan\exception\CryptException;

/**
 * 加解密
 * Class Crypt
 * @author Ryan <43352901@qq.com>
 * @date 2019-06-25 16:59
 * @package ryan\tools
 */
class Crypt
{
    /**
     * 判断数据是否需要异或
     * @param $content string 原数据
     * @param string $xorChar 异或字符
     * @return bool True:需要异或,False:不需要
     */
    public static function isXor($content, $xorChar = 'x')
    {
        $length = strlen($content);
        if ($length > 0) {
            $xorChar = $content[0] ^ $xorChar;
            if (0 === strpos(trim($xorChar), '{')) {
                return true;
            }
        }
        return false;
    }

    /**
     * 对数据异或处理
     * @param $content string 原数据
     * @param string $xorChar 异或字符
     * @return string 异或后的数据
     */
    public static function xorData($content, $xorChar = 'x')
    {
        $data = '';
        $lentgh = strlen($content);
        for ($i = 0; $i < $lentgh; $i++) {
            $data = $data . ($content[$i] ^ $xorChar);
        }
        return $data;
    }

    /**
     * 对请求数据解压，并且异或处理
     * @param $content string 解压前的数据
     * @return string 解压后的数据
     * @throws \Exception 数据解压异常
     */
    public static function unGzip($content)
    {
        try {
            $unzipContent = gzdecode($content);
            if (strlen($unzipContent) === 0) {
                throw new CryptException("No decompression data was found",101);
            }
            return $unzipContent;
        } catch (\Exception $e) {
            throw new CryptException("decompress exception",100);
        }
    }

    /**
     * Gzip数据
     * @param $content string 压缩前的数据
     * @return string 压缩后的数据
     */
    public static function gzip($content)
    {
        $gzipContent = gzencode($content);
        return $gzipContent;
    }

    /**
     * 对请求数据解压，并且异或处理
     * @param $content string 解压前的数据
     * @param string $xorChar 异或字符
     * @return string 解压后的数据
     * @throws \Exception 数据解压异常
     */
    public static function contentDecode($content, $xorChar = 'x')
    {
        try {
            $data = '';
            $unzipContent = gzdecode($content);

            //需要异或
            if (0 !== strpos(trim($unzipContent), '{')) {
                for ($i = 0; $i < strlen($unzipContent); $i++) {
                    $data = $data . ($unzipContent[$i] ^ $xorChar);
                }
            }

            if (strlen($unzipContent) === 0 || strlen($data) === 0) {
                throw new CryptException("No decompression data was found",101);
            }
            return $data;
        } catch (\Exception $e) {
            throw new CryptException("decompress exception",100);
        }
    }

    /**
     * 对返回数据异或并压缩
     * @param $content string 压缩内容
     * @param string $xorChar 异或字符
     * @return string
     */
    public static function contentEncode($content, $xorChar = 'x')
    {
        $jsonStr = '';
        for ($i = 0; $i < strlen($content); $i++) {
            $jsonStr = $jsonStr . ($content[$i] ^ $xorChar);
        }
        $gzipContent = gzencode($jsonStr);
        return $gzipContent;
    }

    /**
     * 判断数据是否是压缩格式
     * @param $data string 需要校验的数据，如果为空则取request->getInput()
     * @return bool true:压缩数据 false:非压缩数据
     */
    public static function isCompressData($data = '')
    {
        if (0 === strpos(trim($data), '{')) {
            return false;
        }
        return true;
    }




}