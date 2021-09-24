<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model\wechat;

use app\common\model\base\A3Mall;

class UsersTags extends A3Mall {

    protected $name = "wechat_users_tags";

    protected $type = [
        "id"=>"integer",
        "count"=>"integer"
    ];

    public function setAppidAttr($value){
        return strip_tags(trim($value));
    }

    public function setNameAttr($value){
        return strip_tags(trim($value));
    }

}