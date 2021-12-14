<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\models\order\Order as OrderModel;
use app\common\models\Area;
use app\common\models\users\Users;
use app\common\models\Payment;

class Order extends OrderModel {

    /**
     * 关联表
     * @return \think\model\relation\HasOne
     */
    public function users(){
        return $this->hasOne(Users::class,"id","user_id")->joinType("LEFT");
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function area(){
        return $this->hasOne(Area::class,"id","province")->joinType("LEFT");
    }

    public function payment(){
        return $this->hasOne(Payment::class,"id","pay_type")->bind(["payment_name"=>"name"])->joinType("LEFT");
    }

}