<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\models\Store;
use app\common\models\StoreUsers as StoreUsersModel;

class StoreUsers extends StoreUsersModel {

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function store(){
        return $this->hasOne(Store::class,'id','shop_id')->joinType("LEFT")->bind(["shop_name"]);
    }

}