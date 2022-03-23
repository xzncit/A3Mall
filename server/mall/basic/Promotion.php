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
            case "1":
                return self::point($data);
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

    private static function point($order){
        if(($point = Db::name("promotion_point")
                ->where("unix_timestamp(now()) between start_time AND end_time")
                ->where(["id"=>$order["activity_id"],"status"=>0])->find()) == false){
            throw new \Exception("您要兑换的商品已过期",0);
        }

        $goods = current($order["item"]);
        $real_point = 0;
        if(isset($goods["spec_key"])){
            $pointItem = Db::name("promotion_point_item")->where([
                "pid"=>$order["activity_id"],
                "spec_key"=>$goods["spec_key"]
            ])->find();

            foreach($order["item"] as $k=>$v){
                $order["item"][$k]["market_price"] = (int)$pointItem["market_price"];
                $order["item"][$k]["real_price"] = (int)$pointItem["sell_price"];
                $order["item"][$k]["sell_price"] = (int)$pointItem["sell_price"];
            }

            if($goods["goods_nums"] > $pointItem["store_nums"]){
                throw new \Exception("商品库存不足",0);
            }

            $real_point = intval($pointItem["sell_price"]) * $goods["goods_nums"];
        }else{
            if($goods["goods_nums"] > $point["store_nums"]){
                throw new \Exception("商品库存不足",0);
            }

            $real_point = $point["point"] * $goods["goods_nums"];
        }

        $order["real_point"] = $real_point;
        $order["payable_amount"] = $order["real_freight"];
        $order["point"] = 0;
        $order["exp"] = 0;
        return $order;
    }

    private static function activity($order){
        $id = $order["activity_id"];
        $num = 0;
        foreach($order["item"] as $val){
            $num += $val["goods_nums"];
        }

        switch ($order["type"]){
            case "2":
                $regiment = Db::name("promotion_regiment")
                    ->where("unix_timestamp(now()) between start_time AND end_time")
                    ->where(["id"=>$id,"status"=>0])->find();

                if(empty($regiment)){
                    throw new \Exception("您要购买的商品团购已结束",0);
                }

                if($num < $regiment["limit_min_count"]){
                    throw new \Exception("团购商品每人只能兑换{$regiment["limit_min_count"]}件",0);
                }else if($num > $regiment["limit_max_count"]){
                    throw new \Exception("团购商品每人只能兑换{$regiment["limit_max_count"]}件",0);
                }

                $goods = current($order["item"]);
                if(isset($goods["spec_key"])){
                    $regimentItem = Db::name("promotion_regiment_item")->where([
                        "pid"=>$order["activity_id"],
                        "spec_key"=>$goods["spec_key"]
                    ])->find();

                    foreach($order["item"] as $k=>$v){
                        $order["item"][$k]["market_price"] = $regimentItem["market_price"];
                        $order["item"][$k]["real_price"] = $regimentItem["sell_price"];
                        $order["item"][$k]["sell_price"] = $regimentItem["sell_price"];
                    }

                    if($goods["goods_nums"] > $regimentItem["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($regimentItem["sell_price"],$goods["goods_nums"]);
                }else{
                    if($goods["goods_nums"] > $regiment["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($regiment["sell_price"],$goods["goods_nums"]);
                }

                $order["real_amount"] = $real_amount;
                $order["payable_amount"] = BC::add($order['real_freight'],$order["real_amount"]);
                $order["point"] = 0;
                $order["exp"] = 0;
                break;
            case "3":
                if(($seckill = Db::name("promotion_second")
                        ->where("unix_timestamp(now()) between start_time AND end_time")
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您要购买的商品秒杀已结束",0);
                }

                $goods = current($order["item"]);
                if(isset($goods["spec_key"])){
                    $secondItem = Db::name("promotion_second_item")->where([
                        "pid"=>$order["activity_id"],
                        "spec_key"=>$goods["spec_key"]
                    ])->find();

                    foreach($order["item"] as $k=>$v){
                        $order["item"][$k]["market_price"] = $secondItem["market_price"];
                        $order["item"][$k]["real_price"] = $secondItem["sell_price"];
                        $order["item"][$k]["sell_price"] = $secondItem["sell_price"];
                    }

                    if($goods["goods_nums"] > $secondItem["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($secondItem["sell_price"],$goods["goods_nums"]);
                }else{
                    if($goods["goods_nums"] > $seckill["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($seckill["sell_price"],$goods["goods_nums"]);
                }

                $order["real_amount"] = $real_amount;
                $order["payable_amount"] = BC::add($order['real_freight'],$order["real_amount"]);
                $order["point"] = 0;
                $order["exp"] = 0;
                break;
            case '4':
                if(($row = Db::name("promotion_price")->where("id",$id)->find()) == false){
                    throw new \Exception("您要购买的商品活动已结束",0);
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
            case "5":
                if(($group = Db::name("promotion_group")
                        ->where("unix_timestamp(now()) between start_time AND end_time")
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您要购买的拼团商品已结束",0);
                }

                $goods = current($order["item"]);
                if(isset($goods["spec_key"])){
                    $groupItem = Db::name("promotion_group_item")->where([
                        "pid"=>$order["activity_id"],
                        "spec_key"=>$goods["spec_key"]
                    ])->find();

                    foreach($order["item"] as $k=>$v){
                        $order["item"][$k]["market_price"] = $groupItem["market_price"];
                        $order["item"][$k]["real_price"] = $groupItem["sell_price"];
                        $order["item"][$k]["sell_price"] = $groupItem["sell_price"];
                    }

                    if($goods["goods_nums"] > $groupItem["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($groupItem["sell_price"],$goods["goods_nums"]);
                }else{
                    if($goods["goods_nums"] > $group["store_nums"]){
                        throw new \Exception("商品库存不足",0);
                    }

                    $real_amount = BC::mul($group["sell_price"],$goods["goods_nums"]);
                }

                $order["real_amount"] = $real_amount;
                $order["payable_amount"] = BC::add($order['real_freight'],$order["real_amount"]);
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
            case "group":
                if(($group = Db::name("promotion_group")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的拼团商品已下架",0);
                }

                $data["activity_id"] = $group["id"];
                $data["goods_id"] = $group["goods_id"];
                break;
            case "second":
                if(($second = Db::name("promotion_second")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的秒杀商品已下架",0);
                }

                $data["activity_id"] = $second["id"];
                $data["goods_id"] = $second["goods_id"];
                break;
            case "point":
                if(($point = Db::name("promotion_point")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的积分商品已下架",0);
                }

                $data["activity_id"] = $point["id"];
                $data["goods_id"] = $point["goods_id"];
                break;
            case "regiment":
                if(($regiment = Db::name("promotion_regiment")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的团购商品已下架",0);
                }

                if($num > $regiment["limit_max_count"]){
                    throw new \Exception("团购商品,只允许购买{$regiment["limit_max_count"]}件");
                }else if($regiment["limit_min_count"] < $num){
                    throw new \Exception("购买商品不能小于{$regiment["limit_min_count"]}件");
                }

                $data["activity_id"] = $regiment["id"];
                $data["goods_id"] = $regiment["goods_id"];
                break;
            case "special":
                if(($special = Db::name("promotion_price")->where(["id"=>$id])->find()) == false){
                    throw new \Exception("您选择的特价商品已下架",0);
                }

                $data["activity_id"] = $special["id"];
                $data["goods_id"] = $special["goods_id"];
                break;
        }

        if(Db::name("goods_item")->where("goods_id",$data["goods_id"])->count()){
            if(($products=Db::name("goods_item")->where("goods_id",$data["goods_id"])->where("id",$product_id)->find()) == false){
                throw new \Exception("您选择的商品已下架",0);
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

        switch($order["type"]){
            case 1: // 积分
                if(!Db::name("promotion_point")->where(["id"=>$order["activity_id"]])->count()){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_point")
                    ->where(["id"=>$order["activity_id"]])
                    ->update([
                        "sum_count"=>Db::raw("sum_count+".$goods["goods_nums"]),
                        "store_nums"=>Db::raw("store_nums-".$goods["goods_nums"])
                    ]);

                break;
            case 2: // 团购
                if(!Db::name("promotion_regiment")->where(["id"=>$order["activity_id"]])->count()){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_regiment")
                    ->where(["id"=>$order["activity_id"]])
                    ->update([
                        "sum_count"=>Db::raw("sum_count+".$goods["goods_nums"]),
                        "store_nums"=>Db::raw("store_nums-".$goods["goods_nums"])
                    ]);
                break;
            case 3: // 秒杀
                if(!Db::name("promotion_second")->where(["id"=>$order["activity_id"]])->count()){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_second")
                    ->where(["id"=>$order["activity_id"]])
                    ->update([
                        "sum_count"=>Db::raw("sum_count+".$goods["goods_nums"]),
                        "store_nums"=>Db::raw("store_nums-".$goods["goods_nums"])
                    ]);
                break;
            case 5:
                if(!$group=Db::name("promotion_group")->where(["id"=>$order["activity_id"]])->find()){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_group")
                    ->where(["id"=>$order["activity_id"]])
                    ->update([
                        "sum_count"=>Db::raw("sum_count+".$goods["goods_nums"]),
                        "store_nums"=>Db::raw("store_nums-".$goods["goods_nums"])
                    ]);

                $kid = Request::param("kid","0","intval");
                $data = [
                    "pid"=>0,
                    "user_id"=>\mall\basic\Users::get("id"),
                    "order_id"=>$order["order_id"],
                    "goods_nums"=>$goods["goods_nums"],
                    "order_amount"=>$order["order_amount"],
                    "group_id"=>$order["activity_id"],
                    "goods_id"=>$goods["goods_id"],
                    "goods_title"=>$goods["title"],
                    "people"=>1,
                    "sell_price"=>$goods["real_price"],
                    "start_time"=>time(),
                    "end_time"=>strtotime("+{$group['effective_time']} hours"),
                    "is_refund"=>0,
                    "status"=>1,
                    "create_time"=>time()
                ];

                if($kid != 0 && ($og = Db::name("order_group")->where([
                    "id"=>$kid,
                    "group_id"=>$order["activity_id"],
                    "goods_id"=>$goods["goods_id"],
                    "is_refund"=>0,
                    "status"=>1
                ])->find()) != false){
                    $data["pid"] = $kid;
                    $data["people"] = $og["people"] + 1;
                    Db::name("order_group")->where("id",$kid)->inc("people")->update();
                }

                Db::name("order_group")->insert($data);
                break;
        }
    }

}
