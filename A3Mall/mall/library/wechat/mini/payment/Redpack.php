<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\mini\payment;

use mall\library\wechat\mini\BasicWeMini;

class Redpack extends BasicWeMini{

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