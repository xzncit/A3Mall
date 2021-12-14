<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\exception;

use Exception;

class BaseException extends Exception {

    private $data = [];

    public function __construct($message = "", $code = 0, $data=[]){
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * 输出信息
     * @return string
     */
    public function getError(){
//        if(env("app_debug")){
//            return "服务器繁忙，请稍后在试";
//        }

        return $this->getMessage();
    }

    public function getRaw(){
        return $this->data;
    }

}