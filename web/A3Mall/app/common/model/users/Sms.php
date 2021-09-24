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

class Sms extends A3Mall{

    protected $name = "users_sms";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setCodeAttr($value){
        return strip_tags(trim($value));
    }

}