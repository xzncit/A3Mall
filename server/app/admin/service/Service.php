<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service;

use app\common\service\Service as ServiceFacade;
use think\facade\Request;

class Service extends ServiceFacade {

    public static function getFields(){
        return [
            "id"=>Request::get("id","0","intval"),
            "name"=>Request::get("name","","strip_tags,trim"),
            "value"=>Request::get("value","0","intval"),
        ];
    }

    /**
     * 生成商品货号
     * @param string $number
     * @param string $date
     * @return string
     */
    public static function goodsNumber($number = '', $date = 'YmdHis') {
        $arr = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $arr[0]), 0, 2) . rand(10, 99);
        return $number . date($date) . $usec;
    }
}