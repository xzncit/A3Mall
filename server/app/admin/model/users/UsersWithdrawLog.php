<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\users;

use app\common\models\users\UsersWithdrawLog as UsersWithdrawLogModel;
use app\common\models\users\Users;

class UsersWithdrawLog extends UsersWithdrawLogModel {

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(["username"])
            ->joinType("LEFT");
    }

}