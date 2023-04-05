<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\wechat;

use app\api\controller\Base;
use app\common\library\wechat\Factory;
use think\facade\Request;
use xzncit\core\message\Raw;
use xzncit\core\Utils;
use app\common\library\wechat\mp\Message;

/**
 * 公众号控制器
 * Class Index
 * @package app\api\controller\wechat
 */
class Index extends Base {

    /**
     * 对接公众号
     * @return string
     */
    public function index(){
        try{
            $response = Factory::wechat()->server->push(function ($data){
                $data = Utils::lowerCase($data ?? []);
                if(empty($data["msgtype"])){
                    throw new \Exception("empty msgtype.",0);
                }

                $message = new Message($data);
                $method = $data["msgtype"];
                if(!empty($method) && method_exists($message,$method)){
                    return $message->$method();
                }else{
                    return new Raw("success");
                }
            });

            return $response->send();
        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }

    /**
     * 获取公众号配置
     * @return \think\response\Json
     */
    public function config(){
        try {
            $sign = Factory::wechat()->script->getJsSign(Request::param("url","","trim"));
            return $this->returnAjax("ok",1,$sign);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

}
