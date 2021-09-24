<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use mall\utils\BC;
use think\facade\Db;
use think\facade\Request;

class Promotion {

    private static $array = [
        "0"=>"满额打折",
        "1"=>"满额优惠金额",
        "2"=>"满额送积分",
        "3"=>"满额免运费"
    ];

    public static function getType($type=''){
        return isset(self::$array[$type]) ? self::$array[$type] : self::$array;
    }

    public static function run($data){
        switch($data["type"]){
            case "2":
            case "3":
            case "4":
            case "5":
                return self::activity($data);
            case "0":
            default:
                return self::discount($data);
        }
    }

    private static function activity($order){
        $id = $order["activity_id"];
        $num = 0;
        foreach($order["item"] as $val){
            $num += $val["goods_nums"];
        }

        switch ($order["type"]){
            case '4':
                if(($row = Db::name("promotion_price")->where("id",$id)->find()) == false){
                    throw new \Exception("您要购买的商品活动己结束",0);
                }

                $price = floatval(Db::name("promotion_price_item")->where([
                    "pid"=>$row["id"],
                    "group_id"=>Users::get("group_id")
                ])->value("price"));

                if($price > 0){
                    foreach($order["item"] as $key=>$val){
                        $order["item"][$key]['sell_price'] = $price;
                        $order["item"][$key]['real_price'] = $price;
                    }

                    $data["promotions"] = $order["promotions"] > 0 ? $order["promotions"]+$price : $price;
                    $order["real_amount"] = $price;
                    $order["payable_amount"] = BC::add($order['real_freight'],$price);
                }

                $order["point"] = 0;
                $order["exp"] = 0;
                break;
        }

        return $order;
    }

    private static function discount($order){
        if(($data = Db::name("promotion_order")
            ->where("unix_timestamp(now()) between start_time AND end_time AND {$order['payable_amount']} >= amount")
            ->order("amount DESC")
            ->find()) == false){
            return $order;
        }

        switch($data["type"]){
            case 0: // 打折
                $payable_amount = ($order["payable_amount"] * $data["expression"]) / 100;
                $order["discount"] = $order["payable_amount"] - $payable_amount;
                $order["payable_amount"] = $payable_amount;
                $order["promotion_explain"] = "购物满" . $data["amount"] . "元，优惠{$order["discount"]}元。";
                break;
            case 1: // 满额优惠
                $order["payable_amount"] -= $data["expression"];
                $order["promotions"] = $data["expression"];
                $order["promotion_explain"] = "购物满" . $data["amount"] . "元，优惠{$data["expression"]}元。";
                break;
            case 2: // 送积分
                $order["point"] += $data["expression"];
                $order["promotion_explain"] = "购物满" . $data["amount"] . "元，赠送{$data["expression"]}积分。";
                break;
            case 3: // 免运费
                $order["real_freight"] = 0;
                $order["promotion_explain"] = "购物满" . $data["amount"] . "元，免运费。";
                break;
        }

        return $order;
    }

    public static function checkOrderType($id,$product_id,$num,$type){
        $data = [
            "goods_id"=>is_array($id) && !empty($id[0]) ? $id[0] : $id,
            "product_id"=>$product_id,
            "nums"=>$num,
            "spec_key"=>"",
            "type"=>$type,
            "activity_id"=>0
        ];

        switch($type){
            case "special":
                if(($special = Db::name("promotion_price")->where(["id"=>$id])->find()) == false){
                    throw new \Exception("您选择的特价商品己下架",0);
                }

                $data["activity_id"] = $special["id"];
                $data["goods_id"] = $special["goods_id"];
                break;
        }

        if(Db::name("goods_item")->where("goods_id",$data["goods_id"])->count()){
            if(($products=Db::name("goods_item")->where("goods_id",$data["goods_id"])->where("id",$product_id)->find()) == false){
                throw new \Exception("您选择的商品己下架",0);
            }

            $data["spec_key"] = $products["spec_key"];
        }

        $data["type"] = Order::getOrderType($type);
        return $data;
    }

    public static function updateStatus($order){
        if($order["type"] == 0){
            return true;
        }
    }

}
