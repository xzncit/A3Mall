<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\common\models\Category;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\order\OrderGroup;

class GroupGoods extends GoodsModel {

    protected $name = "goods";

    public function category(){
        return $this->hasOne(Category::class,'id','cat_id')->joinType("LEFT")->bind([
            "cat_name"=>"title"
        ]);
    }

    public function group(){
        return $this->hasOne(OrderGroup::class,'goods_id','id')->joinType("LEFT")->bind([
            "end_time","people","group_price"=>"sell_price","goods_group_id"=>"id",
        ]);
    }

}