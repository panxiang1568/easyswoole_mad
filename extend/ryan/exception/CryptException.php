<?php

namespace ryan\exception;
class CryptException extends \RuntimeException
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

}