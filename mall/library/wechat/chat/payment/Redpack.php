<?php
namespace mall\library\wechat\chat\payment;

use mall\library\wechat\chat\BasicWePay;

class Redpack extends BasicWePay{

    /**
     * 发放普通红包
     * @param array $options
     * @return array
     */
    public function create(array $options){
        $this->params["wxappid"] = $this->params["appid"];
        unset($this->params["appid"]);
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        return $this->callPost($url, $options, true, 'MD5', false);
    }

    /**
     * 发放裂变红包
     * @param array $options
     * @return array
     */
    public function groups(array $options){
        $this->params["wxappid"] = $this->params["appid"];
        unset($this->params["appid"]);
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack";
        return $this->callPost($url, $options, true, 'MD5', false);
    }

    /**
     * 查询红包记录
     * @param string $mchBillno 商户发放红包的商户订单号
     * @return array
     */
    public function query($mchBillno){
        $this->params["wxappid"] = $this->params["appid"];
        unset($this->params["appid"]);
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo";
        return $this->callPost($url, ['mch_billno' => $mchBillno, 'bill_type' => 'MCHT'], true, 'MD5', false);
    }

}