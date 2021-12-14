<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\payment;


class PaymentException extends \Exception {

    private $raw;

    public function __construct($message = "", $code = 0, $data=[]){
        parent::__construct($message, $code,null);
        $this->raw = $data;
    }

    public function getRaw(){
        return $this->raw;
    }

}