<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Tools\Coupon;

use xzncit\core\base\BasePayment;

class Coupon extends BasePayment {

    /**
     * 发放代金券
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon",$data,true,"HMAC-SHA256",false);
    }

    /**
     * 查询代金券批次
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function query(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock",$data,false,"HMAC-SHA256",false);
    }

    /**
     * 查询代金券信息
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getCouponInfo(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/querycouponsinfo",$data,false,"HMAC-SHA256",false);
    }

}