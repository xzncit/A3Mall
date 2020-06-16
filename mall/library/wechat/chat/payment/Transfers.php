<?php
namespace mall\library\wechat\chat\payment;

use mall\library\wechat\chat\BasicWePay;

class Transfers extends BasicWePay{

    /**
     * 企业付款到零钱
     * @param array $options
     * @return array
     */
    public function create(array $options){
        unset($this->params["appid"]);
        unset($this->params["mch_id"]);
        $this->params["mchid"] = $this->config["mch_id"];
        $this->params["mch_appid"] = $this->config["appid"];
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        return $this->callPost($url, $options, true, 'MD5', false);
    }

    /**
     * 查询企业付款到零钱
     * @param string $partnerTradeNo 商户调用企业付款API时使用的商户订单号
     * @return array
     */
    public function query($partnerTradeNo){
        unset($this->params["mchid"]);
        unset($this->params["mch_appid"]);
        $this->params["appid"] = $this->config["appid"];
        $this->params["mch_id"] = $this->config["mch_id"];
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
        return $this->callPost($url, ['partner_trade_no' => $partnerTradeNo], true, 'MD5', false);
    }

}