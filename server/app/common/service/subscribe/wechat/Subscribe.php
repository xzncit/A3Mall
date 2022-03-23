<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\subscribe\wechat;

use app\common\models\Area as AreaModel;
use app\common\library\wechat\Factory;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\wechat\SubscribeMessage as SubscribeMessageModel;

class Subscribe {

    public static function refund($openid,$order_no){
        if(!$config = self::getConfig("order_refund_template")){
            return false;
        }

        if(!$refund = OrderRefundmentModel::where("order_no",$order_no)->find()){
            return false;
        }

        $data = [];
        foreach($config['attribute'] as $key=>$value){
            if($value["field"] == "first"){
                $data[$value["value"]] = ["value" => "退款处理结果", "color" => "#173177"];
            }else if($value["field"] == "remark"){
                $data[$value["value"]] = ["value" => $refund['pay_status'] == 2 ? '退款成功' : '拒绝退款', "color" => "#173177"];
            }else if($value["field"] == "create_time"){
                $data[$value["value"]] = ["value" => date("Y年m月d日 H:i:s",$refund["create_time"]), "color" => "#173177"];
            }else{
                $field = isset($refund[$value['field']]) ? $refund[$value['field']] : "";
                $data[$value["value"]] = [
                    "value" => $field, "color" => "#173177"
                ];
            }
        }

        try{
            Factory::wechat()->template->send($openid,$config["template_id"],"",$data,"#173177");
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    public static function delivery($openid,$delivery_id){
        if(!$config = self::getConfig("order_delivery_template")){
            return false;
        }

        if(!$orderDelivery = OrderDeliveryModel::where("id",$delivery_id)->find()){
            return false;
        }

        if(!$order = OrderModel::where("id",$orderDelivery["order_id"])->find()){
            return false;
        }

        $data = [];
        foreach($config['attribute'] as $key=>$value){
            if($value["field"] == "first"){
                $data[$value["value"]] = ["value" => "您的订单已发货", "color" => "#173177"];
            }else if($value["field"] == "remark"){
                $data[$value["value"]] = ["value" => '请注意关注签收', "color" => "#173177"];
            }else if($value["field"] == "send_time"){
                $data[$value["value"]] = ["value" => date("Y年m月d日 H:i:s",$order["send_time"]), "color" => "#173177"];
            }else if($value["field"] == "address") {
                $area = AreaModel::getArea([$order["province"],$order["city"],$order["area"]],",");
                $data[$value["value"]] = [
                    "value" => $area . $order["address"],
                    "color" => "#173177"
                ];
            }else{
                $field = isset($refund[$value['field']]) ? $refund[$value['field']] : "";
                $data[$value["value"]] = [
                    "value" => $field, "color" => "#173177"
                ];
            }
        }

        try{
            Factory::wechat()->template->send($openid,$config["template_id"],"",$data,"#173177");
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    public static function pay($openid,$order_id){
        $config = self::getConfig("order_delivery_template");
        if(!$config){
            return false;
        }

        if(!$order = OrderModel::where("id",$order_id)->find()){
            return false;
        }

        if($order['pay_status'] != 1){
            return false;
        }

        $data = [];
        foreach($config['attribute'] as $key=>$value){
            if($value["field"] == "first"){
                $data[$value["value"]] = ["value" => "您的订单已支付成功！", "color" => "#173177"];
            }else if($value["field"] == "remark"){
                $data[$value["value"]] = ["value" => '您还可以在会员中心“我的订单”中查看订单状态。', "color" => "#173177"];
            }else{
                $field = isset($refund[$value['field']]) ? $refund[$value['field']] : "";
                $data[$value["value"]] = [
                    "value" => $field, "color" => "#173177"
                ];
            }
        }

        try{
            Factory::wechat()->template->send($openid,$config["template_id"],"",$data,"#173177");
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    public static function getConfig($sign=""){
        $result = SubscribeMessageModel::where("sign",$sign)->where("status",0)->find();
        if(empty($result) || empty($result["content"])){
            return false;
        }

        if($result["status"]){
            return false;
        }

        $attr = json_decode($result["content"],true);
        if(is_null($attr)){
            return false;
        }

        $array = [];
        foreach($attr["name"] as $k=>$v){
            $value = str_replace(["{{","}}",".DATA"],["","",""],$attr["value"][$k]);
            $array[$k] = [
                "name"=>$v,
                "field"=>$attr["field"][$k],
                "value"=>$value,
            ];
        }

        return [
            "template_id"=>$result["temp_id"],
            "attribute"=>$array
        ];
    }

}