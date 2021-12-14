<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model\order;

use app\common\models\order\Order;
use app\common\models\order\OrderGoods as OrderGoodsModel;

class OrderGoods extends OrderGoodsModel {

    public function order(){
        return $this->hasOne(Order::class,"id","order_id")->bind(["order_no","create_time"])->joinType("LEFT");
    }

}