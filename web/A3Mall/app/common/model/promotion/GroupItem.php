<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\promotion;

use app\common\model\base\A3Mall;

class GroupItem extends A3Mall {

    protected $name = "promotion_group_item";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "store_nums"=>"integer",
        "market_price"=>"float",
        "sell_price"=>"float",
        "cost_price"=>"float",
    ];

    public function setSpecKeyAttr($value){
        return strip_tags(trim($value));
    }

}