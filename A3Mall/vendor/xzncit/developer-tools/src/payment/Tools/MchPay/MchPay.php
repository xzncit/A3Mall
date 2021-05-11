<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Tools\MchPay;

use xzncit\core\base\BasePayment;

/**
 * 企业付款
 * Class MchPay
 * @package xzncit\payment\Tools
 */
class MchPay extends BasePayment {

    /**
     * 企业付款
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function transfers(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers",$data,true,"HMAC-SHA256",false);
    }

    /**
     * 查询企业付款
     * @param $partner_trade_no   商户订单号
     * @return array
     * @throws \Exception
     */
    public function getTransFerInfo($partner_trade_no){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo",[
            "partner_trade_no"=>$partner_trade_no
        ],true,"HMAC-SHA256",false);
    }

    /**
     * 企业付款到银行卡API
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function payBank(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank",$data,true,"MD5",false);
    }

    /**
     * 查询企业付款银行卡API
     * 用于对商户企业付款到银行卡操作进行结果查询，返回付款操作详细结果
     * @param $partner_trade_no
     * @return array
     * @throws \Exception
     */
    public function queryBank($partner_trade_no){
        return $this->request("https://api.mch.weixin.qq.com/mmpaysptrans/query_bank",[
            "partner_trade_no"=>$partner_trade_no
        ],true,"MD5",false);
    }

    public function getPublicKey(){
        return $this->request("https://fraud.mch.weixin.qq.com/risk/getpublickey");
    }

}