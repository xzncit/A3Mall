<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\mini;

use think\Container;

class WeMini {

    public static function __callStatic($method, $params = []){
        $class = __NAMESPACE__ . '\\module\\' . $method;
        return Container::getInstance()->make($class, $params, true);
    }

}