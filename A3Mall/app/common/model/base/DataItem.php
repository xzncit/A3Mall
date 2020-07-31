<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

class DataItem extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "sort"=>"integer",
        "target"=>"integer",
        "status"=>"integer"
    ];

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setUrlAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhotoAttr($value){
        return strip_tags(trim($value));
    }


}