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

class Bonus {

    /**
     * 优惠劵
     */
    public static function apply(&$data,$bonus_id=0){
        $bonus = Db::name("users_bonus")
            ->alias("u")
            ->field("b.*,u.id as users_bonus_id")
            ->join("promotion_bonus b","u.bonus_id=b.id","LEFT")
            ->where("u.user_id",Users::get("id"))
            ->where("u.status",0)
            ->where("u.id",$bonus_id)
            ->where("b.end_time > " . time())->find();

        if(empty($bonus)){
            return $data;
        }

        $price = $bonus["amount"];

        if($price <= 0){
            return $data;
        }

        if($bonus["order_amount"] > $data["real_amount"]){
            return $data;
        }

        $data["promotions"] = $data["promotions"] > 0 ? $data["promotions"] + $price : $price;
        $data["order_amount"] = $data["order_amount"] > $price ? BC::sub($data["order_amount"],$price) : 0.00;
        return $data;
    }

    public static function updateStatus($bonus_id,$order_id){
        $condition = [
            "id"=>$bonus_id,
            "user_id"=>Users::get("id")
        ];

        if(!Db::name("users_bonus")->where($condition)->count()){
            return false;
        }

        return Db::name("users_bonus")->where($condition)->update([
            "status"=>1,
            "order_id"=>$order_id
        ]);
    }
}