<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use think\facade\Request;
use app\api\service\Payment as PaymentService;

class Payment extends Base {

    /**
     * 支付列表
     * @return \think\response\Json
     */
    public function index(){
        try {
            return $this->returnAjax("ok",1,PaymentService::getList(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}