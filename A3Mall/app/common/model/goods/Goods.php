<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\goods;

use app\common\model\base\A3Mall;
use mall\utils\Tool;
use app\common\model\base\Category;

class Goods extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "cat_id"=>"integer",
        "attr_id"=>"integer",
        "brand_id"=>"integer",
        "model_id"=>"integer",
        "delivery_id"=>"integer",
        "goods_weight"=>"float",
        "sell_price"=>"float",
        "market_price"=>"float",
        "cost_price"=>"float",
        "store_nums"=>"integer",
        "point"=>"integer",
        "status"=>"integer",
        "visit"=>"integer",
        "favorite"=>"integer",
        "sort"=>"integer",
        "exp"=>"integer",
        "sale"=>"integer",
        "grade"=>"integer",
        "upper_time"=>"integer",
        "down_time"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer",
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->withJoin("category")->where($condition)->count();
        $data = $this->withJoin("category")->where($condition)->order('goods.id','desc')->paginate($size);

        return [
            "count"=>$count,
            "data"=>$data->items()
        ];
    }

    public function category(){
        return $this->hasOne(Category::class,"id","cat_id")
            ->bind(["cat_name"=>"title"])->joinType("LEFT");
    }

    public function setTitleAttr($value){
        return strip_tags(trim($value));
    }

    public function setGoodsNumberAttr($value){
        return strip_tags(trim($value));
    }

    public function setPhotoAttr($value){
        return strip_tags(trim($value));
    }

    public function setBrieflyAttr($value){
        return strip_tags(trim($value));
    }

//    public function setContentAttr($value){
//        return Tool::editor($value);
//    }

    public function getPhotoAttr($value){
        return Tool::thumb($value,"small");
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
}