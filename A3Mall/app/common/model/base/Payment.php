<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

use mall\utils\Tool;

class Payment extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "type"=>"integer",
        "status"=>"integer",
        "is_show"=>"integer",
        "sort"=>"integer",
        "poundage"=>"float",
        "poundage_type"=>"integer"
    ];

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setCodeAttr($value){
        return strip_tags(trim($value));
    }

    public function setDescriptionAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function setConfigAttr($value){
        return json_encode($value,JSON_UNESCAPED_UNICODE);
    }

    public function getConfigAttr($value){
        return json_decode($value,true);
    }
}