<?php
namespace mall\basic;

use mall\utils\BC;
use think\facade\Db;

class Bonus {

    /**
     * 优惠劵
     */
    public static function apply(&$data,$bonus_id=0){
        if(empty($data["bonus"]['y'])){
            return $data;
        }

        foreach($data["bonus"]['y'] as $key=>$item){
            if($item["id"] == $bonus_id){
                $index = $key;
                break;
            }
        }

        if(empty($data["bonus"]['y'][$index])){
            return $data;
        }

        $bonus = $data["bonus"]['y'][$index];
        $price = $bonus["price"];

        if($price <= 0){
            return $data;
        }

        $data["promotions"] = $data["promotions"] > 0 ? $data["promotions"]+$price : $price;
        $data["order_amount"] = $data["order_amount"] > $price ? BC::sub($data["order_amount"],$price) : 0.00;
        return $data;
    }

    public static function updateStatus($bonus_id,$user_id){
        $condition = [
            "id"=>$bonus_id,
            "user_id"=>$user_id
        ];

        if(!Db::name("users_bonus")->where($condition)->count()){
            return false;
        }

        return Db::name("users_bonus")->where($condition)->update([
            "status"=>1
        ]);
    }
}