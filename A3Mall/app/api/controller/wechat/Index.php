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

        if(!isset($data["sign"])){
            return $this->returnXML(false);
        }

        if(!isset($data["out_trade_no"])){
            return $this->returnXML(false);
        }

        $order = Db::name("order")->where("order_no",$data["out_trade_no"])->find();
        if(empty($order)){
            return $this->returnXML(false);
        }

        if($order["pay_status"] == 1){
            return $this->returnXML(true);
        }

        if(($order["order_amount"] * 100) != $data["total_fee"]){
            return $this->returnXML(false);
        }

        $payment = Db::name("payment")->where("id",$order["pay_type"])->find();
        if(empty($payment)){
            return $this->returnXML(false);
        }

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
