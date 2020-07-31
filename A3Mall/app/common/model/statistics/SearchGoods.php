<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model\statistics;

use app\common\model\base\A3Mall;

class SearchGoods extends A3Mall {

    protected $name = "statistics_search_goods";

    protected $type = [
        "id"=>"integer",
        "goods_id"=>"integer",
        "referer"=>"integer",
        "type"=>"integer",
        "create_time"=>"integer",
    ];

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

}