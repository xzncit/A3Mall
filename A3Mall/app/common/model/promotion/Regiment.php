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

class Regiment extends A3Mall{

    protected $name = "promotion_regiment";

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "store_nums"=>"integer",
        "sum_count"=>"integer",
        "limit_min_count"=>"integer",
        "limit_max_count"=>"integer",
        "status"=>"integer",
        "sell_price"=>"float",
        "sort"=>"integer",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "create_time"=>"integer",
    ];

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return strip_tags(trim($value));
    }

    public function setThumbImageAttr($value){
        return strip_tags(trim($value));
    }
}