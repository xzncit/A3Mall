<?php
namespace mall\basic;

use mall\utils\BC;
use mall\utils\Tool;
use think\facade\Db;

class Cart {

    public static function get($cart){
        $data = [
            "item"=>[],
            "activity_id"=>0,
            "type"=>0,
            "promotions"=>0,
            "discount"=>0,
            "goods_weight"=>0,
            "real_freight"=>0,
            "payable_freight"=>0,
            "real_amount"=>0,
            "payable_amount"=>0,
            "order_total"=>0,
            "order_amount"=>0,
            "exp"=>0,
            "point"=>0
        ];

        foreach($cart as $key=>$val){
            $products = [];
            if(Db::name("goods_item")->where("goods_id",$val["goods_id"])->count()){
                $products = Db::name("goods_item")
                    ->where("goods_id",$val["goods_id"])
                    ->where("spec_key",$val["spec_key"])->find();

                if(empty($products)){
                    throw new \Exception("您选择的商品己下架",0);
                }

                if($val["nums"] > $products["store_nums"]){
                    throw new \Exception("您选择的商品库存不足",0);
                }
            }

            $goods = Db::name("goods")->where("id",$val["goods_id"])->find();
            if(empty($goods)){
                throw new \Exception("您选择的商品己下架",0);
            }

            if(empty($products) && $val["nums"] > $goods["store_nums"]){
                throw new \Exception("您选择的商品库存不足",0);
            }

            $data['item'][$key] = [
                "goods_id"=>$goods["id"],
                "distribution_id"=>$goods["delivery_id"],
                "title"=>$goods["title"],
                "goods_no"=>$goods["goods_number"],
                "thumb_image"=>Tool::thumb($goods["photo"],"medium",true),
                "goods_nums"=>$val["nums"],
                "goods_weight"=>$goods["goods_weight"],
                "market_price"=>$goods["market_price"],
                "real_price"=>$goods["sell_price"],
                "sell_price"=>$goods["sell_price"],
                "goods_array"=>""
                //"cost_price"=>$goods["cost_price"]
            ];

            $data["point"] = BC::add($goods["point"] * $val["nums"],$data["point"],0);
            $data["exp"] = BC::add($goods["exp"] * $val["nums"],$data["exp"],0);

            if(!empty($products)){
                $data['item'][$key]["goods_weight"] = $products["goods_weight"];
                $data['item'][$key]["market_price"] = $products["market_price"];
                $data['item'][$key]["real_price"] = $products["sell_price"];
                $data['item'][$key]["sell_price"] = $products["sell_price"];
                //$data['item'][$key]["cost_price"] = $products["cost_price"];
                $data['item'][$key]["product_id"] = $products["id"];
                $data['item'][$key]["spec_key"] = $products["spec_key"];
                $data['item'][$key]["goods_array"] = Attribute::get($goods["id"],$products["spec_key"]);
                $data["goods_weight"] = BC::add($products["goods_weight"] * $val["nums"],$data["goods_weight"]);
                $data["real_amount"] = BC::add($products["sell_price"] * $val["nums"],$data["real_amount"]);
                $data["payable_amount"] = BC::add($products["sell_price"] * $val["nums"],$data["payable_amount"]);
            }else{
                $data["goods_weight"] = BC::add($goods["goods_weight"] * $val["nums"],$data["goods_weight"]);
                $data["real_amount"] = BC::add($goods["sell_price"] * $val["nums"],$data["real_amount"]);
                $data["payable_amount"] = BC::add($goods["sell_price"] * $val["nums"],$data["payable_amount"]);
            }

            $data["activity_id"] = $val["activity_id"];
            $data["type"] = $val["type"];
        }

        return $data;
    }

    public static function delete($id){
        return Db::name("cart")->where("id","in",$id)->delete();
    }
}