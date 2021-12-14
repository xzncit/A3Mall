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
use app\common\models\users\UsersFavorite as UsersFavoriteModel;

class UsersFavorite extends UsersFavoriteModel {

    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id')->joinType("LEFT")->bind([
            "title"
        ]);
    }

}