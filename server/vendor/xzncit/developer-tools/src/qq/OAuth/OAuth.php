<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\qq\OAuth;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class OAuth extends App {

    /**
     * 登录
     * auth.code2Session
     * 登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。更多使用方法详见 小程序登录。
     * @param $code         登录时获取的 code
     * @return array
     * @throws \Exception
     */
    public function code2Session($code){
        return HttpClient::create()->get("sns/jscode2session",[
            "appid"=>$this->app->config["appid"],
            "secret"=>$this->app->config["appsecret"],
            "js_code"=>$code,
            "grant_type"=>"authorization_code"
        ])->toArray();
    }


}