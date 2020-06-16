<?php
namespace mall\library\wechat\chat;

use think\Container;

class WeChat {

    public static function __callStatic($method, $params = []){
        $class = __NAMESPACE__ . '\\module\\' . $method;
        return Container::getInstance()->make($class, $params, true);
    }

}