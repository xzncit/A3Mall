<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\goods;

use app\common\model\base\A3Mall;

class GoodsItem extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "store_nums"=>"integer",
        "market_price"=>"float",
        "sell_price"=>"float",
        "cost_price"=>"float",
        "goods_weight"=>"float",
    ];

    public function setGoodsNumberAttr($value){
        return strip_tags(trim($value));
    }

    public function setSpecKeyAttr($value){
        return strip_tags(trim($value));
    }

}