<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\order;

use app\common\model\base\A3Mall;

class Log extends A3Mall{

    protected $name = "order_log";

    protected $type = [
        "id"=>"integer",
        "order_id"=>"integer",
        "create_time"=>"integer"
    ];

    public function setUsernameAttr($value){
        return strip_tags(trim($value));
    }

    public function setActionAttr($value){
        return strip_tags(trim($value));
    }

    public function setResultAttr($value){
        return strip_tags(trim($value));
    }

    public function setNoteAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTime($value){
        return date("Y-m-d H:i:s",$value);
    }
}