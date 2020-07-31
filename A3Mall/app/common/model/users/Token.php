<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\users;

use app\common\model\base\A3Mall;

class Token extends A3Mall {

    protected $name = "users_token";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "referer"=>"integer",
        "type"=>"integer",
        "create_time"=>"integer"
    ];

    public function setTokenAttr($value){
        return strip_tags(trim($value));
    }

    public function setSaltAttr($value){
        return strip_tags(trim($value));
    }

    public function setBrandAttr($value){
        return strip_tags(trim($value));
    }

    public function setModelAttr($value){
        return strip_tags(trim($value));
    }

    public function setIpAttr($value){
        return strip_tags(trim($value));
    }

}