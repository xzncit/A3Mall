<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use mall\basic\Order;
use mall\basic\Sms;
use mall\library\wechat\chat\WeChat;
use mall\library\wechat\chat\WeChatMessage;
use mall\library\wechat\chat\WeChatPush;
use mall\utils\Tool;
use think\facade\Db;

class Index {

    public function index(){
        try{
            $wechat = new WeChatPush();
            $receive = $wechat->strToLower($wechat->getReceive());

            $WeChatMessage = new WeChatMessage($wechat);
            if (method_exists($WeChatMessage, ($method = $receive['msgtype']))) {
                return $WeChatMessage->$method();
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }

        return 'success';
    }

    public function notify(){
        try{
            $data = WeChat::Payment()->getNotify();
            $prefix = substr($data["out_trade_no"],0,1);
            if($prefix == "P"){
                if(($rs = Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->find()) != false){
                    Db::startTrans();
                    try{
                        Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->update([
                            "status"=>1,
                            "transaction_id"=>$data["transaction_id"],
                            "pay_time"=>time()
                        ]);

                        Db::name("users")
                            ->where("id",$rs["user_id"])
                            ->inc("amount",$rs["order_amount"])
                            ->update();

                        Db::name("users_log")->insert([
                            "order_no"=>$data["out_trade_no"],
                            "user_id"=>$rs["user_id"],
                            "action"=>0,
                            "operation"=>0,
                            "amount"=>$rs["order_amount"],
                            "description"=>"充值成功，订单号：" . $data["out_trade_no"],
                            "create_time"=>time()
                        ]);

                        Db::commit();
                    }catch (\Exception $ex){
                        Db::rollback();
                    }
                }
            }else{
                $order = Db::name("order")->where("order_no",$data["out_trade_no"])->find();
                if(!empty($order)){
                    Order::payment($data["out_trade_no"]);
                    Db::name("order_log")->insert([
                        'order_id' => $order["id"],
                        'username' => "system",
                        'action' => '付款',
                        'result' => '成功',
                        'note' => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                        'create_time' => time()
                    ]);

                    Sms::send(
                        ["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],
                        "payment_success"
                    );
                }
            }
        }catch (\Exception $e){}

        return WeChat::Payment()->getNotifySuccessReply();
    }

    public function config(){
        try{
            $sign = WeChat::Script()->getJsSign();
        }catch(\Exception $e){
            return json([
                "info"=>$e->getMessage(),
                "status"=>0
            ]);
        }

        return json([
            "info"=>"ok",
            "status"=>1,
            "data"=>$sign
        ]);
    }
}
