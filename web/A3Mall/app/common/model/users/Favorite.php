<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\users;

use app\common\model\base\A3Mall;

class Favorite extends A3Mall {

    protected $name = "users_favorite";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "goods_id"=>"integer",
        "create_time"=>"integer"
    ];

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

}