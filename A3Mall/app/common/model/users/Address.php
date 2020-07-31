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

class Address extends A3Mall {

    protected $name = "users_address";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "country"=>"integer",
        "province"=>"integer",
        "city"=>"integer",
        "area"=>"integer",
        "is_default"=>"integer",
        "create_time"=>"integer",
    ];

    public function setAcceptNameAttr($value){
        return strip_tags(trim($value));
    }

    public function setZipAttr($value){
        return strip_tags(trim($value));
    }

    public function setMobileAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhoneAttr($value){
        return strip_tags(trim($value));
    }

    public function setAddressAttr($value){
        return strip_tags(trim($value));
    }

    public function setExtendsInfoAttr($value){
        return strip_tags(trim($value));
    }

}