<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\pay;

use app\common\library\payment\wechat\WeChat as WeChatPayment;
use xzncit\core\http\Response;

/**
 * 微信支付回调控制器
 * Class Wechat
 * @package app\api\controller\pay
 */
class Wechat {

    /**
     * 异步通知
     * @return string
     */
    public function index(){
        try{
            return (new WeChatPayment())->notify();
        }catch (\Exception $ex){
            return Response::arr2xml(['return_code' => 'FAIL', 'return_msg' => 'ERROR']);
        }
    }

}