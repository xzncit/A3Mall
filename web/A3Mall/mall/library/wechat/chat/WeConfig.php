<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat;

use think\facade\Db;

class WeConfig {

    public static function get($name=""){
        $setting = Db::name("setting")->where("name","in","wechat,wepay,wemini")->select()->toArray();
        $list = [];
        foreach($setting as $key=>$value){
            $list[$value["name"]] = json_decode($value["value"],true);
        }

        if(empty($name)){
            return $list;
        }

        $arr = explode(".",$name);
        if(count($arr) == 1){
            return $list[current($arr)];
        }

        return isset($list[$arr[0]][$arr[1]]) ? $list[$arr[0]][$arr[1]] : null;
    }

}

