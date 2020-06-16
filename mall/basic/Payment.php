<?php
namespace mall\basic;

use think\facade\Db;

class Payment {

    public static function handle($order_id){
        if(($order = Db::name("order")->where("id",$order_id)->find()) == false){
            throw new \Exception("您要支付的订单不存在！",0);
        }

        if(($payment = Db::name("payment")->where("id",$order["pay_type"])->find()) == false){
            throw new \Exception("您选择的支付方式不存在！",0);
        }

        $result = [];
        $users = Db::name("users")->where("id",$order["user_id"])->find();
        switch($payment["code"]){
            case "balance":
                if($order["order_amount"] > $users["amount"]){
                    throw new \Exception("您的余额不足，请充值。",0);
                }

                Db::startTrans();
                try{
                    Db::name("users")
                        ->where("id",$order["user_id"])
                        ->dec("amount",$order["order_amount"])
                        ->update();

                    Order::payment($order["order_no"]);
                    Db::name("order_log")->insert([
                        'order_id' => $order["id"],
                        'username' => "system",
                        'action' => '付款',
                        'result' => '成功',
                        'note' => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                        'create_time' => time()
                    ]);
                    Db::commit();
                }catch(\Exception $e){
                    Db::rollback();
                    throw new \Exception("支付失败，请稍后在试。",-99);
                }

                $result = [
                    "pay"=>0,
                    "order_id"=>$order["id"],
                    "msg"=>"支付成功"
                ];
                break;
            case "wechat":
                break;
            case "wechat-h5":
                break;
        }

        return $result;
    }

}