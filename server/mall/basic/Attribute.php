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

class Attribute {

    public static function get($goods_id,$spec,$json=false){
        $array = explode(",",$spec);
        $data = [];
        foreach($array as $key=>$val){
            $a = explode(":",$val);
            $attr = Db::name("goods_attribute")
                ->where("goods_id",$goods_id)
                ->where("attr_id",$a[0])
                ->where("attr_data_id",$a[1])
                ->find();
            $data[$key] = ["name"=>$attr["name"],"value"=>$attr["value"]];
        }

        return $json ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data;
    }

}