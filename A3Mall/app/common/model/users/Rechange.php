<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\users;

class Rechange extends A3Mall {

    protected $name = "users_rechange";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "pay_type"=>"integer",
        "order_amount"=>"float",
        "status"=>"integer",
        "create_time"=>"integer",
        "pay_time"=>"integer"
    ];

    public function setTransactionIdAttr($value){
        return strip_tags(trim($value));
    }

    public function setOrderNoAttr($value){
        return strip_tags(trim($value));
    }

    public function setPaymentNameAttr($value){
        return strip_tags(trim($value));
    }

}