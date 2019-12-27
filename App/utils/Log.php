<?php

namespace App\utils;

use Swoole\Coroutine as Co;

class Log
{

    public static function write($filename, $content, $flags = FILE_APPEND)
    {
        go(function () use ($filename, $content, $flags) {
            Co::writeFile($filename, $content, $flags);
        });
    }
}