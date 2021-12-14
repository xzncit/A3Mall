<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\payment\balance;

use think\facade\Db;
use app\common\service\order\Order;

/**
 * 余额支付
 * Class Balance
 * @package app\common\library\payment\balance
 */
class Balance {

    /**
     * 支付
     * @param array $order
     * @return array
     * @throws \Exception
     */
    public function pay($order=[]){
        try{
            $users = Db::name("users")->where("id",$order["user_id"])->find();
            if($order["order_amount"] > $users["amount"]){
                throw new \Exception("您的余额不足，请充值。",0);
            }

            Db::startTrans();

            Db::name("users")->where("id",$order["user_id"])->dec("amount",$order["order_amount"])->update();
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

            sendSMS(["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],"payment_success");

            return [
                "pay"       => 0,
                "order_id"  => $order["id"],
                "msg"       => "支付成功"
            ];
        }catch (\Exception $ex){
            Db::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

}