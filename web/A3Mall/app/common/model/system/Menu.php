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

class Menu extends A3Mall{

    protected $name = "system_menu";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "status"=>"integer",
        "sort"=>"integer"
    ];

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setIconAttr($value){
        return strip_tags(trim($value));
    }

    public function setModuleAttr($value){
        return strip_tags(trim($value));
    }

    public function setControllerAttr($value){
        return strip_tags(trim($value));
    }

    public function setMethodAttr($value){
        return strip_tags(trim($value));
    }

    public function setParamAttr($value){
        return strip_tags(trim($value));
    }

    public function setActiveAttr($value){
        return strip_tags(trim($value));
    }

    public function setMenuTableAttr($value){
        return strip_tags(trim($value));
    }

}