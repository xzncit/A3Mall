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

class Bonus extends A3Mall {

    protected $name = "users_bonus";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "order_id"=>"integer",
        "bonus_id"=>"integer",
        "type"=>"integer",
        "used_time"=>"integer",
        "status"=>"integer",
        "create_time"=>"integer"
    ];

}