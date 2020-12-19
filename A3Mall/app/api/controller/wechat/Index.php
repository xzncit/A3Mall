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
use mall\library\wechat\chat\BasicWePay;
use mall\library\wechat\chat\WeChat;
use mall\library\wechat\chat\WeChatMessage;
use mall\library\wechat\chat\WeChatPush;
use mall\library\wechat\mini\BasicWeMini;
use mall\library\wechat\mini\BasicWeMiniPay;
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
        $input = file_get_contents('php://input');
        $data = $this->xml2arr($input);

        // 签名验证sign是否存在
        if(!isset($data["sign"])){
            return $this->returnXML(false);
        }

        // 检查订单号是否存在
        if(!isset($data["out_trade_no"])){
            return $this->returnXML(false);
        }

        $prefix = substr($data["out_trade_no"],0,1);
        // 检查是否为充值订单
        if($prefix == "P"){
            $rechange = Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->find();
            if(empty($rechange)){
                return $this->returnXML(false);
            }

            // 己充值成功的订单直接返回通知微信成功
            if($rechange["status"] == 1){
                return $this->returnXML(true);
            }

            // 按支付方式对签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
            if(($rechange["order_amount"] * 100) != $data["total_fee"]){
                return $this->returnXML(false);
            }

            $payment = Db::name("payment")->where("id",$rechange["pay_type"])->find();
            if(empty($payment)){
                return $this->returnXML(false);
            }

            // 开启事务
            Db::startTrans();
            try{
                switch ($payment["code"]){
                    case "wechat":
                    case "wechat-h5":
                        $pay = new BasicWePay();
                        break;
                    case "wechat-mini":
                        $pay = new BasicWeMiniPay();
                        break;
                    case "wechat-app": // 待实现
                    default:
                        throw new \Exception("您选择的支付方式不存在",0);
                }

                if($pay->getPaySign($data) !== $data['sign']) {
                    throw new \Exception("验证签名错误",0);
                }

                Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->update([
                    "status"=>1,
                    "transaction_id"=>$data["transaction_id"],
                    "pay_time"=>time()
                ]);

                Db::name("users")
                    ->where("id",$rechange["user_id"])
                    ->inc("amount",$rechange["order_amount"])
                    ->update();

                Db::name("users_log")->insert([
                    "order_no"=>$data["out_trade_no"],
                    "user_id"=>$rechange["user_id"],
                    "action"=>0,
                    "operation"=>0,
                    "amount"=>$rechange["order_amount"],
                    "description"=>"充值成功，订单号：" . $data["out_trade_no"],
                    "create_time"=>time()
                ]);

                Db::commit();
            }catch(\Exception $ex){
                Db::rollback();
                Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->update([
                    "status"=>2,
                    "transaction_id"=>$data["transaction_id"],
                    "pay_time"=>time()
                ]);
                return $this->returnXML(false);
            }

            return $this->returnXML(true);
        }

        // 签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
        $order = Db::name("order")->where("order_no",$data["out_trade_no"])->find();
        if(empty($order)){
            return $this->returnXML(false);
        }

        // 充值成功直接通知微信成功
        if($order["pay_status"] == 1){
            return $this->returnXML(true);
        }

        // 按支付方式对签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
        if(($order["order_amount"] * 100) != $data["total_fee"]){
            return $this->returnXML(false);
        }

        $payment = Db::name("payment")->where("id",$order["pay_type"])->find();
        if(empty($payment)){
            return $this->returnXML(false);
        }

        // 开启事务
        Db::startTrans();
        try{
            switch ($payment["code"]){
                case "wechat":
                case "wechat-h5":
                    $pay = new BasicWePay();
                    break;
                case "wechat-mini":
                    $pay = new BasicWeMiniPay();
                    break;
                case "wechat-app": // 待实现
                default:
                    throw new \Exception("您选择的支付方式不存在",0);
            }

            if ($pay->getPaySign($data) !== $data['sign']) {
                throw new \Exception("验证签名错误",0);
            }

            Order::payment($data["out_trade_no"],0,"",$data["transaction_id"]);
            Db::name("order_log")->insert([
                'order_id' => $order["id"],
                'username' => "system",
                'action' => '付款',
                'result' => '成功',
                'note' => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                'create_time' => time()
            ]);

            Db::commit();
        }catch (\Exception $ex){
            Db::rollback();
            return $this->returnXML(false);
        }

        // 不能放在订单的try里否则可能会导致订单执行 Db::rollback()
        try{
            Sms::send(
                ["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],
                "payment_success"
            );
        }catch (\Exception $ex){}

        return $this->returnXML(true);
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

    private function returnXML($status=true){
        if($status){
            return "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
        }

        return "<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[ERROR]]></return_msg></xml>";
    }

    private function xml2arr($xml){
        $entity = libxml_disable_entity_loader(true);
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_disable_entity_loader($entity);
        return json_decode(json_encode($data), true);
    }
}
