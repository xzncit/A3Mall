<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Micropay;

use xzncit\core\base\BasePayment;

/**
 * 付款码支付
 * Class Micropay
 * @package xzncit\payment\Micropay
 */
class Micropay extends BasePayment {

    /**
     * 付款码支付
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/micropay",$data,false);
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
     * 撤销订单
     * 查询字段二选一
     * transaction_id  微信的订单号，建议优先使用
     * out_trade_no    商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function reverse(array $data){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/reverse",$data,true);
    }

    /**
     * 付款码查询openid
     * 通过付款码查询公众号Openid，调用查询后，该付款码只能由此商户号发起扣款，直至付款码更新
     * @param $auth_code    扫码支付付款码，设备读取用户微信中的条码或者二维码信息
     * @return array
     * @throws \Exception
     */
    public function authCodeToOpenid($auth_code){
        return $this->request("https://api.mch.weixin.qq.com/tools/authcodetoopenid",[
            "auth_code"=>$auth_code
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

}