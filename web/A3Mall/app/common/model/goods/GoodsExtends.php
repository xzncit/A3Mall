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

class GoodsExtends extends A3Mall {

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer"
    ];

    public function setAttributeAttr($value){
        return strip_tags(trim($value));
    }
}