<?php
namespace mall\basic;

use think\facade\Db;

class Setting {

    public static function save($name="",$data=null){
        if(empty($data)){
            return false;
        }

        $array["value"] = json_encode($data,JSON_UNESCAPED_UNICODE);
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