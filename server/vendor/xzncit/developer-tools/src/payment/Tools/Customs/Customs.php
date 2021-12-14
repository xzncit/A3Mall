<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\payment\Tools\Customs;

use xzncit\core\base\BasePayment;

class Customs extends BasePayment {

    /**
     * 订单附加信息提交接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function customdeclareorder(array $data){
        return $this->request("https://api.mch.weixin.qq.com/cgi-bin/mch/customs/customdeclareorder",$data,false,"MD5",false);
    }

    /**
     * 订单附加信息查询接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function customdeclarequery(array $data){
        return $this->request("https://api.mch.weixin.qq.com/cgi-bin/mch/customs/customdeclarequery",$data,false,"MD5",false);
    }

    /**
     * 订单附加信息重推接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function customdeclareredeclare(array $data){
        return $this->request("https://api.mch.weixin.qq.com/cgi-bin/mch/newcustoms/customdeclareredeclare",$data,false,"MD5",false);
    }

}