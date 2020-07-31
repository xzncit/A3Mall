<?php

namespace mall\basic;

use think\facade\Db;

class Spread {

    public static function backBrokerage($order){
        // 如果商品有参加活动，则返回
        //if(!empty($order["type"]) && $order["type"] != 0) {
        //    return true;
        //}

        // 获取后台分销类型  1 人人分销
        $setting = Db::name("setting")->where(["name"=>"spread"])->value("value");
        if(empty($setting)){
            return true;
        }

        $setting= json_decode($setting,true);

        // 检查后台是否将分销功能关闭
        if($setting["type"] == 0){
            return true;
        }

        // 支付金额减掉邮费
        $order["spread_price"] = bcsub($order["order_amount"],$order["real_freight"],2);

        $user = Db::name("users")->where(["id"=>$order["user_id"]])->find();
        // 当前用户不存在 没有上级 或者 当用用户上级时自己  直接返回
        if(empty($user)){
            return true;
        }else if($user["spread_id"] <= 0){
            return true;
        }else if($user["spread_id"] == $order["user_id"]){
            return true;
        }

        $order["users"] = $user;

        // 获取后台一级返佣比例
        self::backBrokerageOne($order,$setting);
    }

    public static function backBrokerageOne($order,$setting){
        if($setting["one"] <= 0){
            return true;
        }

        $price = ($order["spread_price"] * $setting["one"]) / 100;
        if($price <= 0){
            return true;
        }

        $user = Db::name("users")->where(["id"=>$order["users"]["spread_id"]])->find();
        if(empty($user)){
            return true;
        }

        $balance = bcadd($user['spread_amount'], $price, 2);
        Db::startTrans();
        try {
            Db::name("users")->where(["id"=>$user["id"]])->update([
                "spread_amount"=>$balance
            ]);
            $description = $order['users']['username'] . '成功消费订单总额' . floatval($order['spread_price']) . '元,奖励推广佣金' . floatval($price);
            Db::name("users_log")->insert([
                "user_id"=>$user["id"],
                "order_no"=>$order["order_no"],
                "admin_id"=>0,
                "action"=>4,
                "operation"=>0,
                "amount"=>$price,
                "description"=>$description,
                "create_time"=>time()
            ]);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }

        $order["users"] = Db::name("users")->where(["id"=>$user["id"]])->find();
        self::backBrokerageTwo($order,$setting);
        return true;
    }

    public static function backBrokerageTwo($order,$setting){
        $user = Db::name("users")->where(["id"=>$order["users"]["spread_id"]])->find();
        if(empty($user)){
            return true;
        }else if($user["spread_id"] == $order["user_id"]){
            return true;
        }

        if($setting["two"] <= 0){
            return true;
        }

        $price = ($order["spread_price"] * $setting["two"]) / 100;
        if($price <= 0){
            return true;
        }

        $balance = bcadd($user['spread_amount'], $price, 2);
        Db::startTrans();
        try {
            Db::name("users")->where(["id"=>$user["id"]])->update([
                "spread_amount"=>$balance
            ]);
            $description = "二级推广人 " . $order["users"]['username'] . '成功消费订单总额' . floatval($order['spread_price']) . '元,奖励推广佣金' . floatval($price);
            Db::name("users_log")->insert([
                "user_id"=>$user["id"],
                "order_no"=>$order["order_no"],
                "admin_id"=>0,
                "action"=>4,
                "operation"=>0,
                "amount"=>$price,
                "description"=>$description,
                "create_time"=>time()
            ]);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }

        $order["users"] = $user;
        self::backBrokerageThree($order,$setting);
    }

    public static function backBrokerageThree($order,$setting){
        $user = Db::name("users")->where(["id"=>$order["users"]["spread_id"]])->find();
        if(empty($user)){
            return true;
        }else if($user["spread_id"] == $order["user_id"]){
            return true;
        }

        if($setting["three"] <= 0){
            return true;
        }

        $price = ($order["spread_price"] * $setting["three"]) / 100;
        if($price <= 0){
            return true;
        }

        $balance = bcadd($user['spread_amount'], $price, 2);
        Db::startTrans();
        try {
            Db::name("users")->where(["id"=>$user["id"]])->update([
                "spread_amount"=>$balance
            ]);
            $description = "三级推广人 " . $order["users"]['username'] . '成功消费订单总额' . floatval($order['spread_price']) . '元,奖励推广佣金' . floatval($price);
            Db::name("users_log")->insert([
                "user_id"=>$user["id"],
                "order_no"=>$order["order_no"],
                "admin_id"=>0,
                "action"=>4,
                "operation"=>0,
                "amount"=>$price,
                "description"=>$description,
                "create_time"=>time()
            ]);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }

        return true;
    }
}