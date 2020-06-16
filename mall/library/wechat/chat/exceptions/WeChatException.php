<?php
namespace mall\library\chat\exceptions;

use Exception;
use Throwable;

class WeChatException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null){
        parent::__construct($message, $code, $previous);
    }

}