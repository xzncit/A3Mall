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

class Distribution {

    private static $_delivery = [];

    public static function get(&$data,$address=[]){
        if(empty($address)){
            return $data;
        }

        foreach($data["item"] as $key=>$value){
            $delivery = self::compute($value["distribution_id"],$address["province"],$value["goods_weight"]);
            $data["item"][$key]["delivery"] = $delivery["delivery"];
            $data["real_freight"] = BC::add($delivery["real_freight"],$data["real_freight"]);
            $data["payable_freight"] = BC::add($delivery["payable_freight"],$data["payable_freight"]);
        }

        $data["payable_amount"] = BC::add($data["real_freight"],$data["real_amount"]);
        $data = Promotion::run($data);
        $data["order_amount"] = $data["payable_amount"];

        return $data;
    }

    private static function compute($delivery_id, $province, $weight){
        $data = [
            "real_freight" => 0, // 实付运费
            "payable_freight" => 0 // 总运费金额
        ];

        $delivery = Db::name("distribution")->where(["status" => 0, "id" => $delivery_id])->find();

        self::$_delivery = $delivery;

        if (empty($delivery)) {
            throw new \Exception("配送方式不存在", 0);
        }

        if ($delivery["type"] == 0) {
            $delivery["weight_price"] = self::calculate($weight, $delivery["first_price"], $delivery["second_price"]);
        }else {
            $index = 0;
            $flag = false;
            $area_groupid = json_decode($delivery["area_group"], true);
            foreach ($area_groupid as $key => $val) {
                $arr = explode(",", $val);
                //foreach ($province as $area_id) {
                    if (in_array($province, $arr)) {
                        $index = $key;
                        $flag = true;
                        break;
                    }
                //}

                if ($flag) {
                    break;
                }
            }

            if ($flag) {
                // 如果查找到所设置的省份
                $firstprice = json_decode($delivery["first_price_group"], true);
                $secondprice = json_decode($delivery["second_price_group"], true);

                $delivery["weight_price"] = self::calculate($weight, $firstprice[$index], $secondprice[$index]);
            } else {
                //如果该地区未设置，使用默认费用
                $delivery["weight_price"] = self::calculate($weight, $delivery["first_price"], $delivery["second_price"]);
            }
        }

        $data["real_freight"] = $delivery["weight_price"];
        $data["payable_freight"] = $delivery["weight_price"];
        $data["delivery"] = ["title" => $delivery["title"], "description" => isset($delivery["description"]) ? $delivery["description"] : ""];
        return $data;
    }

    /*
     * 根据重量计算给定价格
     */
    private static function calculate($weight, $first_price, $second_price) {
        $first_weight = self::$_delivery["first_weight"];
        $second_weight = self::$_delivery["second_weight"];
        //当商品重量小于或等于首重的时候
        if ($weight <= $first_weight) {
            return $first_price;
        }

        //当商品重量大于首重时，根据次重进行累加计算
        $num = ceil(($weight - $first_weight) / $second_weight);
        return $first_price + $second_price * $num;
    }

}