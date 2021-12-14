<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\OAuth;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class OAuth extends App {

    /**
     * 登录
     * auth.code2Session
     * 登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。更多使用方法详见 小程序登录。
     * @param $code         登录时获取的 code
     * @return array
     * @throws \Exception
     */
    public function code2Session($code){
        return HttpClient::create()->get("sns/jscode2session",[
            "appid"=>$this->app->config["appid"],
            "secret"=>$this->app->config["appsecret"],
            "js_code"=>$code,"grant_type"=>"authorization_code"
        ])->toArray();
    }

    /**
     * 用户信息
     * auth.getPaidUnionId
     * 用户支付完成后，获取该用户的 UnionId，无需用户授权。本接口支持第三方平台代理查询。
     * 注意：调用前需要用户完成支付，且在支付后的五分钟内有效。
     * @param $openid                   支付用户唯一标识
     * @param string $transaction_id    微信支付订单号
     * @param string $mch_id            微信支付分配的商户号，和商户订单号配合使用
     * @param string $out_trade_no      微信支付商户订单号，和商户号配合使用
     * @return array
     * @throws \Exception
     */
    public function getPaidUnionId($openid,$transaction_id="",$mch_id="",$out_trade_no=""){
        $data = ["openid"=>$openid];
        is_null($transaction_id) || $data["transaction_id"] = $transaction_id;
        is_null($mch_id) || $data["mch_id"] = $mch_id;
        is_null($out_trade_no) || $data["out_trade_no"] = $out_trade_no;
        return HttpClient::create()->get("wxa/getpaidunionid?access_token=ACCESS_TOKEN",$data)->toArray();
    }

}