<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Order;

use xzncit\core\base\BasePayment;
use xzncit\core\http\Response;
use xzncit\core\Utils;

/**
 * H5支付/JSAPI支付/扫码支付/APP支付/小程序支付 统一订单支付类
 * Class Order
 * @package xzncit\payment\Order
 */
class Order extends BasePayment {

    /**
     * 统一下单
     * @param array $data []
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/unifiedorder",$data,false, 'MD5');
    }

    /**
     * 调起JSAPI支付
     * @param string $prepayId 统一下单预支付码
     * @return array
     */
    public function jsApi($prepayId){
        $data = [
            "appId"     => $this->app->config["appid"],
            "timeStamp" => (string)time(),
            "nonceStr"  => Utils::getRandString(),
            "package"   => "prepay_id={$prepayId}",
            "signType"  => "MD5"
        ];

        $data["paySign"] = $this->getPaySign($data, 'MD5');
        // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写
        $data['timestamp'] = $data['timeStamp'];
        return $data;
    }

    /**
     * 调起APP支付接口
     * @param $prepayId 统一下单预支付码
     * @return array
     */
    public function appApi($prepayId){
        $data = [
            'appid'     => $this->app->config["appid"],
            'partnerid' => $this->app->config["mch_id"],
            'prepayid'  => (string)$prepayId,
            'package'   => 'Sign=WXPay',
            'timestamp' => (string)time(),
            'noncestr'  => Utils::getRandString(),
        ];

        $data['sign'] = $this->getPaySign($data, 'MD5');
        return $data;
    }

    /**
     * 小程序调起支付API
     * @param $prepayId 统一下单预支付码
     * @return array
     */
    public function wxaApi($prepayId){
        $data = [
            "appId"     => $this->app->config["appid"],
            "nonceStr"  => Utils::getRandString(),
            "package"   => "prepay_id=". $prepayId,
            "signType"  => "MD5",
            "timeStamp" => (string)time(),
        ];

        $data['paySign'] = $this->getPaySign($data, 'MD5');
        return $data;
    }

    /**
     * 查询订单
     * 查询字段二选一
     * transaction_id  微信的订单号，建议优先使用
     * out_trade_no    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function query(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/orderquery",$data,false,"MD5");
    }

    /**
     * 关闭订单
     * @param string $out_trade_no    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     * @return array
     * @throws \Exception
     */
    public function close($out_trade_no){
        return $this->request("https://api.mch.weixin.qq.com/pay/closeorder",[
            "out_trade_no"=>$out_trade_no
        ],false,"MD5");
    }

    /**
     * 转换短链接
     * @param $url
     * @return array
     * @throws \Exception
     */
    public function shortUrl($url){
        return $this->request("https://api.mch.weixin.qq.com/tools/shorturl",[
            "long_url"=>$url
        ],false);
    }

    /**
     * 拉取订单评价数据
     * 商户可以通过该接口拉取用户在微信支付交易记录中针对你的支付记录进行的评价内容。
     * 商户可结合商户系统逻辑对该内容数据进行存储、分析、展示、客服回访以及其他使用。
     * 如商户业务对评价内容有依赖，可主动引导用户进入微信支付交易记录进行评价。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function batchQueryComment(array $data){
        return $this->request("https://api.mch.weixin.qq.com/billcommentsp/batchquerycomment",$data,true);
    }

    /**
     * 获取微信支付通知
     * @return array
     */
    public function getNotify(){
        $data = Response::xml2arr(file_get_contents('php://input'));
        if (isset($data['sign']) && $this->getPaySign($data) === $data['sign']) {
            return $data;
        }

        throw new \Exception('Invalid Notify!', 0);
    }

    /**
     * 微信支付通知回复内容
     * @return string
     */
    public function getNotifySuccessReply(){
        return Response::arr2xml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
    }

    /**
     * 微信支付通知回复内容
     * @return string
     */
    public function getNotifyErrorReply(){
        return Response::arr2xml(['return_code' => 'FAIL', 'return_msg' => 'ERROR']);
    }

}