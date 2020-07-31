<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;

class Queue extends A3Mall {

    protected $name = "system_queue";

    protected $type = [
        "id"=>"integer",
        "type"=>"integer",
        "exec_pid"=>"integer",
        "exec_time"=>"integer",
        "start_time"=>"float",
        "end_time"=>"float",
        "loops_time"=>"integer",
        "count"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

    public function setCodeAttr($value){
        return strip_tags(trim($value));
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setCommandAttr($value){
        return strip_tags(trim($value));
    }

    public function setExecDataAttr($value){
        return strip_tags(trim($value));
    }

    public function setExecDescAttr($value){
        return strip_tags(trim($value));
    }
}