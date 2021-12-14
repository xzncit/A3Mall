<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\order;

use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\order\Order;
use app\common\models\users\Users;
use app\common\models\goods\Freight;

class OrderDelivery extends OrderDeliveryModel {

    public function order(){
        return $this->hasOne(Order::class,"id","order_id")
            ->bind(["order_no"])->joinType("LEFT");
    }

    public function orderFreight(){
        return $this->hasOne(Freight::class,"id","freight_id")
            ->bind(["title"])->joinType("LEFT");
    }

    public function users(){
        return $this->hasOne(Users::class,"id","user_id")
            ->bind(['username'])->joinType("LEFT");
    }

}