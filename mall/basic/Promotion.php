<?php
namespace mall\basic;

use mall\utils\BC;
use think\facade\Db;
use mall\basic\Users;

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
            throw new \Exception("您要兑换的商品己过期",0);
        }

        $num = 0;
        foreach($order["item"] as $val){
            $num += $val["goods_nums"];
        }

        if($num > $point["limit_max_count"]){
            throw new \Exception("积分商品每人只能兑换{$point["limit_max_count"]}件",0);
        }else if($num > $point["store_nums"]){
            throw new \Exception("商品库存不足",0);
        }

        $order["real_point"] = $point["price"] * $num;
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
                $group = Db::name("promotion_regiment")
                    ->where("unix_timestamp(now()) between start_time AND end_time")
                    ->where(["id"=>$id,"status"=>0])->find();

                if(empty($group)){
                    throw new \Exception("您要购买的商品团购己结束",0);
                }

                if($num < $group["limit_min_count"]){
                    throw new \Exception("团购商品每人只能兑换{$group["limit_min_count"]}件",0);
                }else if($num > $group["limit_max_count"]){
                    throw new \Exception("团购商品每人只能兑换{$group["limit_max_count"]}件",0);
                }else if($num > $group["store_nums"]){
                    throw new \Exception("商品库存不足",0);
                }

                foreach($order["item"] as $key=>$val){
                    $order["item"][$key]['real_price'] = $group["regiment_price"];
                }

                $order["payable_amount"] = BC::mul($group["regiment_price"],$num);
                $order["point"] = 0;
                $order["exp"] = 0;
                break;
            case "3":
                if(($group = Db::name("promotion_seckill")
                        ->where("unix_timestamp(now()) between start_time AND end_time")
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您要购买的商品秒杀己结束",0);
                }

                if($num > $group["limit_count"]){
                    throw new \Exception("秒杀商品每人只能兑换{$group["limit_count"]}件",0);
                }else if($num > $group["store_nums"]){
                    throw new \Exception("商品库存不足",0);
                }

                foreach($order["item"] as $key=>$val){
                    $order["item"][$key]['real_price'] = $group["sell_price"];
                }

                $order["payable_amount"] = BC::mul($group["sell_price"],$num);
                $order["point"] = 0;
                $order["exp"] = 0;
                break;
            case '4':
                if(($row = Db::name("promotion_price")->where("id",$id)->find()) == false){
                    throw new \Exception("您要购买的商品活动己结束",0);
                }

                $price = floatval(Db::name("promotion_price_item")->where([
                    "pid"=>$row["id"],
                    "group_id"=>Users::get("group_id")
                ])->value("price"));

                if($price > 0){
                    $data["promotions"] = $order["promotions"] > 0 ? $order["promotions"]+$price : $price;
                    $order["payable_amount"] = $order["payable_amount"] > $price ? BC::sub($order["payable_amount"],$price) : $order["payable_amount"];
                }
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
            "goods_id"=>$id,
            "product_id"=>$product_id,
            "nums"=>$num,
            "spec_key"=>"",
            "type"=>$type,
            "activity_id"=>0
        ];

        switch($type){
            case "second":
                if(($second = Db::name("promotion_seckill")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的秒杀商品己下架",0);
                }

                $data["activity_id"] = $second["id"];
                $data["goods_id"] = $second["goods_id"];
                break;
            case "point":
                if(($point = Db::name("promotion_point")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的积分商品己下架",0);
                }

                $data["activity_id"] = $point["id"];
                $data["goods_id"] = $point["goods_id"];
                break;
            case "group":
                if(($regiment = Db::name("promotion_regiment")
                        ->where("store_nums",">","0")
                        ->where("end_time",">",time())
                        ->where(["id"=>$id,"status"=>0])->find()) == false){
                    throw new \Exception("您选择的团购商品己下架",0);
                }

                $data["activity_id"] = $regiment["id"];
                $data["goods_id"] = $regiment["goods_id"];
                break;
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

        switch($order["type"]){
            case 1: // 积分
                if(($point = Db::name("promotion_point")
                        ->where(["id"=>$order["activity_id"]])->find()) == false){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_point")
                    ->where(["id"=>$order["activity_id"]])
                    ->inc("sum_count",$goods["goods_nums"])->update();
                break;
            case 2: // 团购
                if(($group = Db::name("promotion_regiment")
                        ->where(["id"=>$order["activity_id"]])->find()) == false){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_regiment")
                    ->where(["id"=>$order["activity_id"]])
                    ->inc("sum_count",$goods["goods_nums"])->update();
                break;
            case 3: // 秒杀
                if(($seckill = Db::name("promotion_seckill")
                        ->where(["id"=>$order["activity_id"]])->find()) == false){
                    return true;
                }

                $goods = current($order["item"]);
                Db::name("promotion_seckill")
                    ->where(["id"=>$order["activity_id"]])
                    ->inc("sum_count",$goods["goods_nums"])->update();
                break;
        }
    }

}
