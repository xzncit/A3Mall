<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\payment;

use app\common\library\payment\wechat\WeChat;
use app\common\library\payment\balance\Balance;
use mall\basic\Users;
use mall\utils\CString;
use think\facade\Db;
use app\common\models\Setting as SettingModel;
use app\common\service\order\Order;

class Payment {

    private static $instance;
    protected $order = [];

    private function __construct() {}

    public static function instance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 设置订单数据
     * @param $order_id
     * @return $this
     * @throws PaymentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setOrderData($order_id){
        if(!$order = Db::name("order")->where("id",$order_id)->find()){
            throw new \Exception("您要支付的订单不存在！",0);
        }

        if($order["pay_status"] == 1){
            throw new PaymentException("您的订单己支付，请勿重复支付。",0,[
                "pay"       => 99,
                "order_id"  => $order["id"],
                "msg"       => "您的订单己支付，请勿重复支付。"
            ]);
        }

        // 检查是否为积分订单
        if($order["type"] == 1){
            Db::name("users")->where("id",Users::get("id"))->update([
                "point"=>Db::raw("point-".$order["real_point"])
            ]);

            Db::name("users_log")->insert([
                "user_id"       => Users::get("id"),
                "order_no"      => $order["order_no"],
                "action"        => 1,
                "operation"     => 1,
                "point"         => $order["real_point"],
                "description"   => "成功购买了订单号：{$order["order_no"]}中的商品,积分减少{$order["real_point"]}",
                "create_time"   => time()
            ]);
        }

        // 如果订单金额小于等于0 支付成功
        if($order["order_amount"] <= 0){
            Db::name("order_log")->insert([
                'order_id'    => $order["id"],
                'username'    => "system",
                'action'      => '付款',
                'result'      => '成功',
                'note'        => '订单【' . $order["order_no"] . '】付款' . $order["order_amount"] . '元',
                'create_time' => time()
            ]);

            Order::payment($order["order_no"]);

            throw new PaymentException("支付成功",1,[
                "pay"         => 0,
                "order_id"    => $order["id"],
                "msg"         => "支付成功"
            ]);
        }

        $goods_array = Db::name("order_goods")->where("order_id",$order_id)->order("id","asc")->value("goods_array");
        $goods_title = SettingModel::getStringData("web_name");
        if(!empty($goods_array)){
            $goods_array = json_decode($goods_array,true);
            $goods_title = "-" . CString::msubstr($goods_array["title"],30,false);
        }

        $order["web_title"] = $goods_title;
        $this->order = $order;
        return $this;
    }

    public function pay($payment){
        $payType = explode("-",$payment["code"]);
        $method = isset($payType[1]) ? $payType[1] : $payType[0];
        switch($payType[0]){
            case "wechat":
                return (new WeChat())->$method($this->order);
            case "balance":
                return (new Balance())->pay($this->order);
        }
    }

    public function getPayType($payment_id,$source){
        switch($payment_id){
            case "wechat":
                return $this->getWechatID($source);
            default:
                return $payment_id;
        }
    }

    public function getWechatID($source){
        switch($source){
            case 1:
                return "wechat-h5";
            case 2:
                return "wechat-mp";
            case 3:
                return "wechat-mini";
            case 4:
                return "wechat-app";
            case 5:
                return "wechat-qrcode";
        }
    }

}