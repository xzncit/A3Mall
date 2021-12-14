<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\users;

use app\common\models\goods\Goods;
use app\common\models\users\Users;
use app\common\models\users\UsersSuggest as UsersSuggestModel;

class UsersSuggest extends UsersSuggestModel {

    public function users(){
        return $this->hasOne(Users::class,'id','user_id')
            ->joinType("LEFT")->bind([
                "username"
            ]);
    }

}