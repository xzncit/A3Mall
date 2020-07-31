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
use mall\utils\Tool;

class Point extends A3Mall {

    protected $name = "promotion_point";

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "store_nums"=>"integer",
        "sum_count"=>"integer",
        "status"=>"integer",
        "point"=>"integer",
        "sort"=>"integer",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "create_time"=>"integer"
    ];

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function setThumbImageAttr($value){
        return strip_tags(trim($value));
    }
}