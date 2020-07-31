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
use mall\utils\Tool;

class UsersLog extends A3Mall{

    protected $name = "system_users_log";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "type"=>"integer",
        "time"=>"integer"
    ];

    public function getList($condition=[],$size=10){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order("id","DESC")->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function setIntroAttr($value){
        return Tool::editor($value);
    }

    public function setIpAttr($value){
        return strip_tags(trim($value));
    }

    public function getTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}