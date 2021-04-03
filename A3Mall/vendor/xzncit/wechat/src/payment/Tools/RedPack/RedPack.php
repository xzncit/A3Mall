<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Tools\RedPack;

use xzncit\core\base\BasePayment;

class RedPack extends BasePayment {

    /**
     * 发放红包接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack",$data,true,"HMAC-SHA256",false);
    }

    /**
     * 发放裂变红包
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function sendGroupRedPack(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack",$data,true,"HMAC-SHA256",false);
    }

    /**
     * 查询红包记录
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function query(array $data){
        return $this->request("https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo",$data,true,"HMAC-SHA256",false);
    }
}