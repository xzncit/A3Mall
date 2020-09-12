<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

use mall\library\wechat\chat\WeChat;
use think\facade\Db;
use think\facade\Request;

class Payment {

    public static function handle($order_id){
        if(($order = Db::name("order")->where("id",$order_id)->find()) == false){
            throw new \Exception("您要支付的订单不存在！",0);
        }

        if(($payment = Db::name("payment")->where("id",$order["pay_type"])->find()) == false){
            throw new \Exception("您选择的支付方式不存在！",0);
        }

        if($order["pay_status"] == 1){
            return [
                "pay"=>99,
                "order_id"=>$order["id"],
                "msg"=>"您的订单己支付，请勿重复支付。"
            ];
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

                try{
                    Sms::send(
                        ["mobile"=>$order["mobile"],"order_no"=>$order["order_no"]],
                        "payment_success"
                    );
                }catch (\Exception $ex){}

                $result = [
                    "pay"=>0,
                    "order_id"=>$order["id"],
                    "msg"=>"支付成功"
                ];
                break;
            case "wechat":
                $wecatUsers = Db::name("wechat_users")->where("user_id",$users["id"])->find();
                if(empty($wecatUsers["openid"])){
                    $result = [
                        "pay"=>99,
                        "order_id"=>$order["id"],
                        "msg"=>"用户授权获取OPENID失败。"
                    ];
                }else{
                    try{
                        $web_name = Setting::get("web_name");
                        $rs = WeChat::Payment()->createOrder([
                            'body'             => $web_name,
                            'openid'           => $wecatUsers["openid"],
                            'total_fee'        => $order["order_amount"] * 100,
                            'trade_type'       => 'JSAPI',
                            'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                            'out_trade_no'     => $order["order_no"],
                            'spbill_create_ip' => Request::ip(),
                        ]);
                        $options = WeChat::Payment()->createParamsForJsApi($rs["prepay_id"]);
                        $result = [
                            "pay"=>1,
                            "order_id"=>$order["id"],
                            "msg"=>"ok",
                            "result"=>[
                                "options"=>$options,
                                "config"=>WeChat::Script()->getJsSign(Request::post("url","") ? Request::post("url","") : Request::domain())
                            ]
                        ];
                    }catch(\Exception $e){
                        $result = [
                            "pay"=>99,
                            "order_id"=>$order["id"],
                            "msg"=>$e->getMessage()
                        ];
                    }
                }
                break;
            case "wechat-h5":
                try{
                    $web_name = Setting::get("web_name");
                    $rs = WeChat::Payment()->createOrder([
                        'body'             => $web_name,
                        'total_fee'        => $order["order_amount"] * 100,
                        'trade_type'       => 'MWEB',
                        'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                        'out_trade_no'     => $order["order_no"],
                        'spbill_create_ip' => Request::ip(),
                        'scene_info'       => json_encode([
                            "h5_info"=>[
                                "type"=>"Wap",
                                "wap_url"=>Request::domain(),
                                "wap_name"=>$web_name
                            ]
                        ],JSON_UNESCAPED_UNICODE)
                    ]);

                    $result = [
                        "pay"=>1,
                        "order_id"=>$order["id"],
                        "msg"=>"ok",
                        "result"=>[
                            "url"=>$rs["mweb_url"]
                        ]
                    ];
                }catch(\Exception $e){
                    $result = [
                        "pay"=>99,
                        "order_id"=>$order["id"],
                        "msg"=>$e->getMessage()
                    ];
                }
                break;
        }

        return $result;
    }

    public static function rechang($payment_code,$price=0){
        if(empty($price) || $price <= 0){
            throw new \Exception("请输入您要充值的金额！",0);
        }

        if(($payment = Db::name("payment")->where("code",$payment_code)->find()) == false){
            throw new \Exception("您选择的支付方式不存在！",0);
        }

        $orderNo = "P".Order::orderNo();
        $result = [];
        $users = Db::name("users")->where("id",Users::get("id"))->find();
        Db::name("users_rechange")->insert([
            "user_id"=>Users::get("id"),
            "pay_type"=>$payment["id"],
            "order_no"=>$orderNo,
            "order_amount"=>$price,
            "payment_name"=>$payment["name"],
            "status"=>0,
            "create_time"=>time()
        ]);
        $order_id = Db::name("users_rechange")->getLastInsID();
        switch($payment["code"]){
            case "wechat":
                $wecatUsers = Db::name("wechat_users")->where("user_id",$users["id"])->find();
                if(empty($wecatUsers["openid"])){
                    $result = [
                        "pay"=>99,
                        "msg"=>"用户授权获取OPENID失败。"
                    ];
                }else{
                    try{
                        $web_name = Setting::get("web_name");
                        $rs = WeChat::Payment()->createOrder([
                            'body'             => $web_name,
                            'openid'           => $wecatUsers["openid"],
                            'total_fee'        => $price * 100,
                            'trade_type'       => 'JSAPI',
                            'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                            'out_trade_no'     => $orderNo,
                            'spbill_create_ip' => Request::ip(),
                        ]);
                        $options = WeChat::Payment()->createParamsForJsApi($rs["prepay_id"]);
                        $result = [
                            "pay"=>1,
                            "order_id"=>$order_id,
                            "msg"=>"ok",
                            "result"=>[
                                "options"=>$options,
                                "order_no"=>$orderNo,
                                "config"=>WeChat::Script()->getJsSign(Request::post("url","") ? Request::post("url","") : Request::domain())
                            ]
                        ];

                    }catch(\Exception $e){
                        Db::name("users_rechange")->where("id",$order_id)->where("user_id",Users::get("id"))->update(["status"=>2]);
                        $result = [
                            "pay"=>99,
                            "order_id"=>$order_id,
                            "msg"=>$e->getMessage()
                        ];
                    }
                }
                break;
            case "wechat-h5":
                try{
                    $web_name = Setting::get("web_name");
                    $rs = WeChat::Payment()->createOrder([
                        'body'             => $web_name,
                        'total_fee'        => $price * 100,
                        'trade_type'       => 'MWEB',
                        'notify_url'       => createUrl('api/wechat/notify', [], false, true),
                        'out_trade_no'     => $orderNo,
                        'spbill_create_ip' => Request::ip(),
                        'scene_info'       => json_encode([
                            "h5_info"=>[
                                "type"=>"Wap",
                                "wap_url"=>Request::domain(),
                                "wap_name"=>$web_name
                            ]
                        ],JSON_UNESCAPED_UNICODE)
                    ]);

                    $result = [
                        "pay"=>1,
                        "order_id"=>$order_id,
                        "msg"=>"ok",
                        "result"=>[
                            "url"=>$rs["mweb_url"]
                        ]
                    ];
                }catch(\Exception $e){
                    Db::name("users_rechange")->where("id",$order_id)->where("user_id",Users::get("id"))->update(["status"=>2]);
                    $result = [
                        "pay"=>99,
                        "order_id"=>$order_id,
                        "msg"=>$e->getMessage()
                    ];
                }
                break;
        }

        return $result;
    }
}