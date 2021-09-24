<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\mini\payment;

use mall\library\wechat\mini\BasicWeMiniPay;
use mall\library\wechat\chat\lib\Utils;

class Order extends BasicWeMiniPay {

    /**
     * 统一下单
     * @param array $options
     * @return array
     */
    public function create(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        return $this->callPost($url, $options, false, 'MD5');
    }

    /**
     * 刷卡支付
     * @param array $options
     * @return array
     */
    public function micropay(array $options){
        $url = 'https://api.mch.weixin.qq.com/pay/micropay';
        return $this->callPost($url, $options, false, 'MD5');
    }

    /**
     * 查询订单
     * @param array $options
     * @return array
     */
    public function query(array $options){
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        return $this->callPost($url, $options);
    }

    /**
     * 关闭订单
     * @param string $outTradeNo 商户订单号
     * @return array
     */
    public function close($outTradeNo){
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        return $this->callPost($url, ['out_trade_no' => $outTradeNo]);
    }

    /**
     * 创建JsApi及H5支付参数
     * @param string $prepayId 统一下单预支付码
     * @return array
     */
    public function jsapiParams($prepayId){
        $option = [];
        $option["appId"] = $this->config["appid"];
        $option["timeStamp"] = (string)time();
        $option["nonceStr"] = Utils::createRandString();
        $option["package"] = "prepay_id={$prepayId}";
        $option["signType"] = "MD5";
        $option["paySign"] = $this->getPaySign($option, 'MD5');
        $option['timestamp'] = $option['timeStamp'];
        return $option;
    }

    /**
     * 获取支付规则二维码
     * @param string $productId 商户定义的商品id或者订单号
     * @return string
     */
    public function qrcParams($productId){
        $data = [
            'appid'      => $this->config["appid"],
            'mch_id'     => $this->config["mch_id"],
            'time_stamp' => (string)time(),
            'nonce_str'  => Tools::createNoncestr(),
            'product_id' => (string)$productId,
        ];
        $data['sign'] = $this->getPaySign($data, 'MD5');
        return "weixin://wxpay/bizpayurl?" . http_build_query($data);
    }

    /**
     * 获取微信App支付参数
     * @param string $prepayId 统一下单预支付码
     * @return array
     */
    public function appParams($prepayId){
        $data = [
            'appid'     => $this->config["appid"],
            'partnerid' => $this->config["mch_id"],
            'prepayid'  => (string)$prepayId,
            'package'   => 'Sign=WXPay',
            'timestamp' => (string)time(),
            'noncestr'  => Utils::createRandString(),
        ];
        $data['sign'] = $this->getPaySign($data, 'MD5');
        return $data;
    }

    /**
     * 获取微信小程序支付参数
     * @param string $prepayId 统一下单预支付码
     * @return array
     * MD5(
     * appId=wxd678efh567hg6787&
     * nonceStr=5K8264ILTKCH16CQ2502SI8ZNMTM67VS&
     * package=prepay_id=wx2017033010242291fcfe0db70013231072&signType=MD5&
     * timeStamp=1490840662&key=qazwsxedcrfvtgbyhnujmikolp111111
     * )
     */
    public function createParamsWxApp($prepayId){
        $data = [
            "appId"     => $this->config["appid"],
            "nonceStr"  => Utils::createRandString(),
            "package"   => "prepay_id=". $prepayId,
            "signType"  => "MD5",
            "timeStamp" => (string)time(),
        ];

        $data['paySign'] = $this->getPaySign($data, 'MD5');
        return $data;
    }

    /**
     * 刷卡支付 撤销订单
     * @param array $options
     * @return array
     */
    public function reverse(array $options){
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
        return $this->callPost($url, $options, true);
    }

    /**
     * 刷卡支付 授权码查询openid
     * @param string $authCode 扫码支付授权码，设备读取用户微信中的条码或者二维码信息
     * @return array
     */
    public function queryAuthCode($authCode){
        $url = 'https://api.mch.weixin.qq.com/tools/authcodetoopenid';
        return $this->callPost($url, ['auth_code' => $authCode]);
    }

    /**
     * 刷卡支付 交易保障
     * @param array $options
     * @return array
     */
    public function report(array $options){
        $url = 'https://api.mch.weixin.qq.com/payitil/report';
        return $this->callPost($url, $options);
    }
}