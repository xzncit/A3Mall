<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\payment\wechat;

use mall\basic\Users;
use mall\utils\Tool;
use think\facade\Db;
use xzncit\Factory;
use app\common\models\Setting as SettingModel;
use app\common\library\wechat\Factory as FactoryWeChat;
use app\common\library\payment\PaymentException;
use app\common\models\Payment as PaymentModel;
use app\common\service\order\Order;

class WeChat {

    protected $config = [];

    /**
     * 公众号支付
     * @param array $order
     * @return array
     * @throws PaymentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mp($order=[]){
        $users = Db::name("wechat_users")->where("user_id",Users::get("id"))->find();

        if(empty($users)){
            throw new PaymentException("用户授权获取OPENID失败",[
                "pay"              => 99,
                "order_id"         => $order["id"],
                "msg"              => "用户授权获取OPENID失败。"
            ]);
        }

        try{
            $result = $this->setConfig()->payment()->order->create([
                'body'             => $order["web_title"],
                'openid'           => $users["openid"],
                'total_fee'        => $order["order_amount"] * 100,
                'trade_type'       => 'JSAPI',
                'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                'out_trade_no'     => $order["order_no"],
                'spbill_create_ip' => getIP(),
            ]);

            return [
                "pay"              => 1,
                "order_id"         => $order["id"],
                "msg"              => "ok",
                "result"=>[
                    "options"      => $this->setConfig()->payment()->order->jsApi($result["prepay_id"]),
                    "config"       => FactoryWeChat::wechat()->script->getJsSign(input("post.url","") ? input("post.url") : getDomain())
                ]
            ];
        }catch (\Exception $ex){
            throw new PaymentException($ex->getMessage(),0,[
                "pay"              => 99,
                "order_id"         => $order["id"],
                "msg"              => $ex->getMessage()
            ]);
        }


    }

    /**
     * 移动端网页支付
     * @param array $order
     * @return array
     * @throws PaymentException
     */
    public function h5($order=[]){
        try{
            $result = $this->setConfig()->payment()->order->create([
                'body'             => $order["web_title"],
                'total_fee'        => $order["order_amount"] * 100,
                'trade_type'       => 'MWEB',
                'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                'out_trade_no'     => $order["order_no"],
                'spbill_create_ip' => getIP(),
                'scene_info'       => json_encode([
                    "h5_info"=>[
                        "type"     => "Wap",
                        "wap_url"  => getDomain(),
                        "wap_name" => explode("-",$order["web_title"])[0]
                    ]
                ],JSON_UNESCAPED_UNICODE)
            ]);

            return [
                "pay"              => 2,
                "order_id"         => $order["id"],
                "msg"              => "ok",
                "result"=>[
                    "url"          => $result["mweb_url"]
                ]
            ];
        }catch (\Exception $ex){
            throw new PaymentException($ex->getMessage(),$ex->getCode(),[
                "pay"              => 99,
                "order_id"         => $order["id"],
                "msg"              => $ex->getMessage()
            ]);
        }
    }

    /**
     * APP支付
     * @param array $order
     * @return array
     * @throws PaymentException
     */
    public function app($order=[]){
        $config = (new PaymentModel())->getPayConfig("wechat-app");

        if(empty($config)){
            throw new \Exception("请配置微信支付");
        }

        if(empty($config["app_id"])){
            throw new \Exception("请配置微信支付 - appid");
        }

        if(empty($config["mch_id"])){
            throw new \Exception("请配置微信支付 - mch_id");
        }

        try{
            $result = $this->setConfig()->payment([
                "appid"     => trim($config["app_id"]),
                "mch_id"    => trim($config["mch_id"]),
                "mch_key"   => trim($config["mch_key"]),
                "ssl_cer"   => Tool::getRootPath() . trim($config["cert_url"],"/"),
                "ssl_key"   => Tool::getRootPath() . trim($config["key_url"],"/")
            ])->order->create([
                'body'             => $order["web_title"],
                'total_fee'        => $order["order_amount"] * 100,
                'trade_type'       => 'APP',
                'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                'out_trade_no'     => $order["order_no"],
                'spbill_create_ip' => getDomain(),
            ]);

            return [
                "pay"              => 1,
                "order_id"         => $order["id"],
                "msg"              => "ok",
                "result"           => [
                    "params"       => $this->setConfig()->payment()->order->appApi($result["prepay_id"])
                ]
            ];
        }catch (\Exception $ex){
            throw new PaymentException($ex->getMessage(),$ex->getCode(),[
                "pay"              => 99,
                "order_id"         => $order["id"],
                "msg"              => $ex->getMessage()
            ]);
        }
    }

    public function notify(){
        $obj = $this->setConfig()->payment()->order;
        $data = $obj->getNotify();

        // 签名验证sign是否存在
        if(!isset($data["sign"])){
            return $obj->getNotifyErrorReply();
        }

        // 检查订单号是否存在
        if(!isset($data["out_trade_no"])){
            return $obj->getNotifyErrorReply();
        }

        $prefix = substr($data["out_trade_no"],0,1);
        // 检查是否为充值订单
        if($prefix == "P"){
            $rechange = Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->find();
            if(empty($rechange)) return $obj->getNotifyErrorReply();

            // 己充值成功的订单直接返回通知微信成功
            if($rechange["status"] == 1) return $obj->getNotifySuccessReply();

            // 按支付方式对签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
            if(($rechange["order_amount"] * 100) != $data["total_fee"]){
                return $obj->getNotifyErrorReply();
            }

            try{
                // 开启事务
                Db::startTrans();
                Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->update([
                    "status"=>1,
                    "transaction_id"=>$data["transaction_id"],
                    "pay_time"=>time()
                ]);

                Db::name("users")->where("id",$rechange["user_id"])->inc("amount",$rechange["order_amount"])->update();

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
                return $obj->getNotifySuccessReply();
            }catch(\Exception $ex){
                Db::rollback();
                Db::name("users_rechange")->where("order_no",$data["out_trade_no"])->update([
                    "status"=>2,
                    "transaction_id"=>$data["transaction_id"],
                    "pay_time"=>time()
                ]);
                return $obj->getNotifyErrorReply();
            }
        }

        // 签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
        $order = Db::name("order")->where("order_no",$data["out_trade_no"])->find();
        if(empty($order)) return $obj->getNotifyErrorReply();

        // 充值成功直接通知微信成功
        if($order["pay_status"] == 1) return $obj->getNotifySuccessReply();

        // 按支付方式对签名验证,并校验返回的订单金额是否与商户侧的订单金额一致
        if(($order["order_amount"] * 100) != $data["total_fee"]){
            return $obj->getNotifyErrorReply();
        }

        try{
            // 开启事务
            Db::startTrans();
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
            sendSMS(["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],"payment_success");
            return $obj->getNotifySuccessReply();
        }catch(\Exception $ex){
            Db::rollback();
            return $obj->getNotifyErrorReply();
        }
    }

    /**
     * 创建支付对象
     * @param array $config
     * @return \xzncit\payment\Payment
     */
    public function payment(array $config=[]){
        if(empty($config)) $config = $this->config;
        return Factory::Payment($config);
    }

    /**
     * 获取支付配置
     * @return $this
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setConfig(){
        $config = SettingModel::getArrayData("wepay");
        $config["appid"] = SettingModel::getArrayData("wechat.appid");
        $this->config = [
            "appid"     => trim($config["appid"]),
            "mch_id"    => trim($config["mch_id"]),
            "mch_key"   => trim($config["mch_key"]),
            "ssl_cer"   => Tool::getRootPath() . trim($config["cert_url"],"/"),
            "ssl_key"   => Tool::getRootPath() . trim($config["key_url"],"/")
        ];

        return $this;
    }
}