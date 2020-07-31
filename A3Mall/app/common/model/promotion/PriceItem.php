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

class PriceItem extends A3Mall {

    protected $name = "promotion_price_item";

    protected $type = [
        "id"=>"integer",
        "pid"=>"integer",
        "group_id"=>"integer",
        "price"=>"float",
    ];
}