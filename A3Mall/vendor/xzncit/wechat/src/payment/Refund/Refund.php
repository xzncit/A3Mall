<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Refund;

use xzncit\core\base\BasePayment;
use xzncit\core\base\Prpcrypt;
use xzncit\core\http\Response;

class Refund extends BasePayment {

    /**
     * 申请退款
     * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，卖家可以通过退款接口将支付款退还给买家，
     * 微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return $this->request("https://api.mch.weixin.qq.com/secapi/pay/refund",$data,true);
    }

    /**
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * 注意：如果单个支付订单部分退款次数超过20次请使用退款单号查询
     * @param array $data
     * transaction_id   微信订单号      微信订单号查询的优先级是： refund_id > out_refund_no > transaction_id > out_trade_no
     * out_trade_no     商户订单号      商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一。
     * out_refund_no    商户退款单号    商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     * refund_id        微信退款单号    微信生成的退款单号，在申请退款接口有返回
     * 以上字段四选一
     * offset           偏移量         偏移量，当部分退款次数超过10次时可使用，表示返回的查询结果从这个偏移量开始取记录
     * @return array
     * @throws \Exception
     */
    public function query(array $data){
        return $this->request("https://api.mch.weixin.qq.com/pay/refundquery",$data,false);
    }

    /**
     * 退款通知
     * @return array
     * @throws \Exception
     */
    public function getNotify(){
        $result = Response::xml2arr(file_get_contents("php://input"));
        if (!isset($result['return_code']) || $result['return_code'] !== 'SUCCESS') {
            throw new \Exception('Invalid Notify！');
        }

        $prpcrypt = new Prpcrypt(md5($this->app->config["mch_key"]));
        $array = $prpcrypt->decrypt(base64_decode($result["req_info"]));
        if (intval($array[0]) > 0) {
            throw new \Exception($array[1], $array[0]);
        }

        $result['decode'] = $array[1];
        return $result;
    }

}