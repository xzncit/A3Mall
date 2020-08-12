<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;
use mall\utils\Tool;

class Setting extends A3Mall{

    protected $type = [
        "id"=>"integer"
    ];

    public function saveConfigData($name="",$data=null){
        if(empty($data)){
            return false;
        }

        $array["value"] = is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data;
        if(($obj=$this->where(["name"=>$name])->find()) != false){
            $obj->where(["name"=>$name])->save($array);
        }else{
            $array["name"] = $name;
            $this->save($array);
        }

        return true;
    }

    public function getConfigData($name="",$json=true){
        $content = $this->where(["name"=>$name])->value("value");
        if(empty($content)){
            return "";
        }

        return $json ? json_decode($content,true) : $content;
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setValueAttr($value){
        return Tool::editor($value);
    }
}