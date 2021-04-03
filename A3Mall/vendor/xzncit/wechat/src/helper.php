<?php
namespace xzncit\help;

use xzncit\core\Cache;
use xzncit\core\Logger;

if(!function_exists("cache")){
    function cache(){
        return Cache::create();
    }
}

if(!function_exists("logger")){
    function logger(){
        return Logger::getInstance();
    }
}
