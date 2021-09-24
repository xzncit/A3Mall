<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat\payment;

use mall\library\wechat\chat\BasicWePay;

class Coupon extends BasicWePay{

    /**
     * 发放代金券
     * @param array $options
     * @return array
     */
    public function create(array $options){
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon";
        return $this->callPost($url, $options, true);
    }

    /**
     * 查询代金券批次
     * @param array $options
     * @return array
     */
    public function queryStock(array $options){
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock";
        return $this->callPost($url, $options, false);
    }

    /**
     * 查询代金券信息
     * @param array $options
     * @return array
     */
    public function queryInfo(array $options){
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock";
        return $this->callPost($url, $options, false);
    }

}
