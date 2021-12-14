<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\microapp\OAuth;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class OAuth extends App {

    /**
     * 登录
     * 登录凭证校验。通过 tt.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。
     * @param $code          login 接口返回的登录凭证
     * @param $anonymousCode login 接口返回的匿名登录凭证
     * @return array
     * @throws \Exception
     * Tip：对于同一个用户，不同的宿主或不同的开发者得到的 unionid 是不同的。
     * Tip：session_key 会随着login接口的调用被刷新。可以通过checkSession方法验证当前 session 是否有效，从而避免频繁登录。
     * Tip：session_key 会话密钥 session_key 是对用户数据进行 加密签名 的密钥。为了应用自身的数据安全，开发者服务器不应该把会话密钥下发到小程序，也不应该对外提供这个密钥。
     * return array [
     *  "session_key": "会话密钥，如果请求时有 code 参数才会返回",
     *  "openid": "用户在当前小程序的 ID，如果请求时有 code 参数才会返回",
     *  "anonymous_openid": "匿名用户在当前小程序的 ID，如果请求时有 anonymous_code 参数才会返回",
     *  "unionid": "用户在小程序平台的唯一标识符，请求时有 code 参数才会返回。如果开发者拥有多个小程序，可通过 unionid 来区分用户的唯一性。",
     * ]
     */
    public function code2Session($code,$anonymousCode=""){
        return HttpClient::create()->postJson("api/apps/v2/jscode2session",[
            "appid"=>$this->app->config["appid"],
            "secret"=>$this->app->config["appsecret"],
            "code"=>$code,
            "anonymous_code"=>$anonymousCode
        ])->toArray();
    }

}