<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\model;


class BonusModel extends A3Mall {

    protected $name = "promotion_bonus";
    protected $type = [
        "id"            => "integer",
        "amount"        => "float",
        "type"          => "integer",
        "point"         => "integer",
        "giveout"       => "integer",
        "used"          => "integer",
        "order_amount"  => "float",
        "status"        => "integer",
        "start_time"    => "integer",
        "end_time"      => "integer",
        "create_time"   => "integer"
    ];

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

}