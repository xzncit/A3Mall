<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\base;

use app\common\model\goods\Goods;
use app\common\model\goods\GoodsAttribute;
use app\common\model\goods\GoodsItem;
use mall\basic\Users;
use mall\utils\Tool;

class Cart extends A3Mall{

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "goods_id"=>"integer",
        "product_id"=>"integer",
        "sell_price"=>"float",
        "cost_price"=>"float",
        "market_price"=>"float",
        "goods_weight"=>"float",
        "goods_nums"=>"integer",
        "create_time"=>"integer",
        "update_time"=>"integer",
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();

        $total = ceil($count/$size);
        if($total == $page -1){
            throw new \Exception("没有数据了哦！",-1);
        }

        $result = $this->where($condition)->order("id","DESC")->paginate($size);

        $data = [];
        $goodsModel = new Goods();
        $goodsItemModel = new GoodsItem();
        $goodsAttributeModel = new GoodsAttribute();
        foreach($result->items() as $key=>$value){
            $goods = $goodsModel
                ->where("id",$value["goods_id"])
                ->where("status",0)->find();

            if(empty($goods)){
                $this->where("id",$value["id"])->delete();
                continue;
            }

            if($goodsItemModel->where([
                    "goods_id"=>$value["goods_id"]
                ])->count() && $value["product_id"] <= 0){
                $this->where("id",$value["id"])->delete();
                continue;
            }

            $data[$key] = [
                "id"=>$value["id"],
                "title"=>$goods["title"],
                "price"=>$goods["sell_price"],
                "photo"=>Tool::thumb($goods["photo"],"medium",true),
                "nums"=>$goods["store_nums"],
                "goods_nums"=>$value["goods_nums"],
                "goods_id"=>$value["goods_id"],
                "product_id"=>$value["product_id"],
            ];

            if($value["product_id"] > 0){
                $products = $goodsItemModel->where([
                    "goods_id"=>$value["goods_id"],
                    "spec_key"=>$value["spec_key"],
                ])->find();

                if(empty($products)){
                    unset($data[$key]);
                    $this->where("id",$value["id"])->delete();
                    continue;
                }

                $arr = explode(",",$value["spec_key"]);
                $attr = [];
                foreach ($arr as $val){
                    $spec = explode(":",$val);
                    $attribute = $goodsAttributeModel->where([
                        "goods_id"=>$value["goods_id"],
                        "attr_id"=>$spec[0],
                        "attr_data_id"=>$spec[1]
                    ])->find();
                    $attr[] = $attribute["name"] . ":" . $attribute["value"];
                }

                $data[$key]["attr"] = implode(",",$attr);
                $data[$key]["price"] = $products["sell_price"];
                $data[$key]["nums"] = $products["store_nums"];
                $data[$key]["product_id"] = $products["id"];

            }
        }

        return [
            "count"=>$count,
            "total"=>$total,
            "data"=>$data
        ];
    }

    public function setSessionIdAttr($value){
        return strip_tags($value);
    }

    public function setSpecKeyAttr($value){
        return strip_tags(trim($value));
    }

    public function getCreateTimeAttr($value){
        return strip_tags(trim($value));
    }

    public function getUpdateTimeAttr($value){
        return strip_tags(trim($value));
    }

}