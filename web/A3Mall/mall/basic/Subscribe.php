<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\basic;

use think\facade\Db;
use mall\library\wechat\mini\WeMini;
use mall\basic\Setting;

class Subscribe {

    /**
     * 退款通知
     */
    public static function refundNotice($openid,$order_no){
        $config = self::getConfig("refund_notice");
        if(!$config){
            return false;
        }

        $refund = Db::name("order_refundment")->where("order_no",$order_no)->find();
        if(empty($refund)){
            return false;
        }

        $data = [];

        foreach($config['attribute'] as $key=>$value){
            $field = isset($refund[$value['field']]) ? $refund[$value['field']] : "";
            if(in_array($value["field"],['create_time','dispose_time'])){
                $field = date("Y年m月d日 H:i:s",$field);
            }else if($value["field"] == 'pay_status'){
                $field = $refund['pay_status'] == 2 ? '退款成功' : '拒绝退款';
            }

            $data[$value["value"]] = [
                "value" => $field
            ];
        }

        try {
            WeMini::SubscribeMessage()->send([
                "touser"=>$openid,
                "template_id"=>$config["template_id"],
                "data"=>$data,
                "miniprogram_state"=>self::getMiniprogramState(),
                "lang"=>"zh_CN"
            ]);

            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 配送通知
     */
    public static function deliveryNotice($openid,$delivery_id){
        $config = self::getConfig("delivery_notice");
        if(!$config){
            return false;
        }

        $orderDelivery = Db::name("order_delivery")->where("id",$delivery_id)->find();
        if(empty($orderDelivery)){
            return false;
        }

        $order = Db::name("order")->where("id",$orderDelivery["order_id"])->find();
        if(empty($order)){
            return false;
        }

        $orderDelivery["order_no"] = $order["order_no"];

        $data = [];

        foreach($config['attribute'] as $key=>$value){
            $field = isset($orderDelivery[$value['field']]) ? $orderDelivery[$value['field']] : "";
            if($value["field"] == "{{custom}}"){
                $field = "您的订单己发货";
            }

            $data[$value["value"]] = [
                "value" => $field
            ];
        }

        try {
            WeMini::SubscribeMessage()->send([
                "touser"=>$openid,
                "template_id"=>$config["template_id"],
                "data"=>$data,
                "miniprogram_state"=>self::getMiniprogramState(),
                "lang"=>"zh_CN"
            ]);

            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 订单完成通知
     */
    public static function orderComplete($openid,$order_id){
        $config = self::getConfig("order_complete");
        if(!$config){
            return false;
        }

        $order = Db::name("order")->where("id",$order_id)->find();
        if(empty($order)){
            return false;
        }

        if($order['status'] != 5){
            return false;
        }

        $data = [];

        foreach($config['attribute'] as $key=>$value){
            $field = isset($order[$value['field']]) ? $order[$value['field']] : "";
            if(in_array($value["field"],['create_time','dispose_time'])){
                $field = date("Y年m月d日 H:i:s",$field);
            }else if($value["field"] == '{{custom}}'){
                $field = '完成交易';
            }

            $data[$value["value"]] = [
                "value" => $field
            ];
        }

        try {
            WeMini::SubscribeMessage()->send([
                "touser"=>$openid,
                "template_id"=>$config["template_id"],
                "data"=>$data,
                "miniprogram_state"=>self::getMiniprogramState(),
                "lang"=>"zh_CN"
            ]);

            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 订单支付成功通知
     */
    public static function orderPaySuccess($openid,$order_id){
        $config = self::getConfig("order_pay_success");
        if(!$config){
            return false;
        }

        $order = Db::name("order")->where("id",$order_id)->find();
        if(empty($order)){
            return false;
        }

        if($order['pay_status'] != 1){
            return false;
        }

        $data = [];

        foreach($config['attribute'] as $key=>$value){
            $field = isset($order[$value['field']]) ? $order[$value['field']] : "";
            if(in_array($value["field"],['create_time','pay_time'])){
                $field = date("Y年m月d日 H:i:s",$field);
            }else if($value["field"] == 'pay_status'){
                $field = '支付成功';
            }

            $data[$value["value"]] = [
                "value" => $field
            ];
        }

        try {
            WeMini::SubscribeMessage()->send([
                "touser"=>$openid,
                "template_id"=>$config["template_id"],
                "data"=>$data,
                "miniprogram_state"=>self::getMiniprogramState(),
                "lang"=>"zh_CN"
            ]);

            return true;
        }catch (\Exception $ex){}

        return false;
    }

    public static function getMiniprogramState(){
        $config = Setting::get("wemini_base");
        return isset($config["mini_status"]) ? $config["mini_status"] : "trial";
    }

    public static function getConfig($sign=""){
        $result = Db::name("wechat_mini_subscribe_message")->where("sign",$sign)->find();
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