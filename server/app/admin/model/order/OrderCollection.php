<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\order;

use app\common\models\order\OrderCollection as OrderCollectionModel;
use app\common\models\order\Order;
use app\common\models\Payment;
use app\common\models\users\Users;

class OrderCollection extends OrderCollectionModel {

    public function order(){
        return $this->hasOne(Order::class,"id","order_id")->bind(["order_no"])->joinType("LEFT");
    }

    public function payment(){
        return $this->hasOne(Payment::class,"id","payment_id")->bind(["name"])->joinType("LEFT");
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")->bind(['username'])->joinType("LEFT");
    }

}