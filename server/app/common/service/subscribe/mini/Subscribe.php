<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\subscribe\mini;

use app\common\service\Service;
use app\common\library\wechat\Factory;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\Setting as SettingModel;
use app\common\models\wechat\SubscribeMessage as SubscribeMessageModel;

class Subscribe extends Service {

    /**
     * 退款通知
     */
    public static function refundNotice($openid,$order_no){
        if(!$config = self::getConfig("refund_notice")){
            return false;
        }

        if(!$refund = OrderRefundmentModel::where("order_no",$order_no)->find()){
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
            Factory::mini()->subscribe_message->send($openid,$config["template_id"],$data,self::getMiniprogramState());
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 配送通知
     */
    public static function deliveryNotice($openid,$delivery_id){
        if(!$config = self::getConfig("delivery_notice")){
            return false;
        }

        if(!$orderDelivery = OrderDeliveryModel::where("id",$delivery_id)->find()){
            return false;
        }

        if(!$order = OrderModel::where("id",$orderDelivery["order_id"])->find()){
            return false;
        }

        $orderDelivery["order_no"] = $order["order_no"];
        $data = [];
        foreach($config['attribute'] as $key=>$value){
            $field = isset($orderDelivery[$value['field']]) ? $orderDelivery[$value['field']] : "";
            if($value["field"] == "{{custom}}"){
                $field = "您的订单已发货";
            }

            $data[$value["value"]] = [
                "value" => $field
            ];
        }

        try {
            Factory::mini()->subscribe_message->send($openid,$config["template_id"],$data,self::getMiniprogramState());
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 订单完成通知
     */
    public static function orderComplete($openid,$order_id){
        if(!$config = self::getConfig("order_complete")){
            return false;
        }

        if(!$order = OrderModel::where("id",$order_id)->find()){
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

            $data[$value["value"]] = [ "value" => $field ];
        }

        try {
            Factory::mini()->subscribe_message->send($openid,$config["template_id"],$data,self::getMiniprogramState());
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 订单支付成功通知
     */
    public static function orderPaySuccess($openid,$order_id){
        if(!$config = self::getConfig("order_pay_success")){
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
            $field = isset($order[$value['field']]) ? $order[$value['field']] : "";
            if(in_array($value["field"],['create_time','pay_time'])){
                $field = date("Y年m月d日 H:i:s",$field);
            }else if($value["field"] == 'pay_status'){
                $field = '支付成功';
            }

            $data[$value["value"]] = [ "value" => $field ];
        }

        try {
            Factory::mini()->subscribe_message->send($openid,$config["template_id"],$data,self::getMiniprogramState());
            return true;
        }catch (\Exception $ex){}

        return false;
    }

    /**
     * 获取小程序状态
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getMiniprogramState(){
        $config = SettingModel::getArrayData("wemini_base");
        return isset($config["mini_status"]) ? $config["mini_status"] : "trial";
    }

    /**
     * 获取配置
     * @param string $sign
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
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

        return [ "template_id"=>$result["temp_id"], "attribute"=>$array ];
    }

}