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

class Group extends A3Mall{

    protected $name = "promotion_group";

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "people"=>"integer",
        "sell_price"=>"float",
        "sort"=>"integer",
        "sum_count"=>"integer",
        "store_nums"=>"integer",
        "status"=>"integer",
        "start_time"=>"integer",
        "end_time"=>"integer",
        "effective_time"=>"integer",
        "browse"=>"integer",
        "create_time"=>"integer",
    ];

    public function setThumbImageAttr($value){
        return strip_tags(trim($value));
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setInfoAttr($value){
        return strip_tags(trim($value));
    }

    public function setContentAttr($value){
        return Tool::editor($value);
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getStartTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function getEndTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}