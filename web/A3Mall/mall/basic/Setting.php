<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use think\facade\Db;

class Setting {

    public static function save($name="",$data=null){
        if(empty($data)){
            return false;
        }

        $array["value"] = is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data;
        if(Db::name("setting")->where(["name"=>$name])->count()){
            Db::name("setting")->where(["name"=>$name])->update($array);
        }else{
            $array["name"] = $name;
            Db::name("setting")->insert($array);
        }

        return true;
    }

    public static function get($name="",$json=false){
        $content = Db::name("setting")->where(["name"=>$name])->value("value");
        if(empty($content)){
            return "";
        }

        return $json ? $content : json_decode($content,true);
    }
}