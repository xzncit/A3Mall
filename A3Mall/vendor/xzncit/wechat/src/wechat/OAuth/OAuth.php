<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\OAuth;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class OAuth extends App {

    /**
     * Oauth 授权跳转接口
     * @param string $redirect_url 授权回跳地址
     * @param string $state 为重定向后会带上state参数（填写a-zA-Z0-9的参数值，最多128字节）
     * @param string $scope 授权类类型(可选值snsapi_base|snsapi_userinfo)
     * @return string
     */
    public function getOauthRedirect($redirect_url, $state = '', $scope = 'snsapi_base'){
        $appid = $this->app->config["appid"];
        $redirect_uri = urlencode($redirect_url);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * 通过code换取网页授权access_token
     * 首先请注意，这里通过code换取的是一个特殊的网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同。
     * 公众号可通过下述接口来获取网页授权access_token。如果网页授权的作用域为snsapi_base，
     * 则本步骤中获取到网页授权access_token的同时，也获取到了openid，snsapi_base式的网页授权流程即到此为止。
     *
     * 尤其注意：由于公众号的secret和获取到的access_token安全级别都非常高，必须只保存在服务器，不允许传给客户端。
     * 后续刷新access_token、通过access_token获取用户信息等步骤，也必须从服务器发起。
     * @param $code         填写第一步获取的code参数
     * @return bool|array
     */
    public function getOauthAccessToken($code){
        $appid = $this->app->config['appid'];
        $appsecret = $this->app->config['appsecret'];
        return HttpClient::create()->get("sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code")->toArray();
    }

    /**
     * 刷新access_token
     * 由于access_token拥有较短的有效期，当access_token超时后，可以使用refresh_token进行刷新，
     * refresh_token有效期为30天，当refresh_token失效之后，需要用户重新授权。
     * @param string $refresh_token     填写通过access_token获取到的refresh_token参数
     * @return bool|array
     */
    public function getOauthRefreshToken($refresh_token){
        $appid = $this->app->config['appid'];
        return HttpClient::create()->get("sns/oauth2/refresh_token?appid={$appid}&grant_type=refresh_token&refresh_token={$refresh_token}")->toArray();
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param string $access_token  网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid        用户的唯一标识
     * @return array
     */
    public function checkOauthAccessToken($access_token, $openid){
        return HttpClient::create()->get("sns/auth?access_token={$access_token}&openid={$openid}")->toArray();
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * 如果网页授权作用域为snsapi_userinfo，则此时开发者可以通过access_token和openid拉取用户信息了。
     * @param string $access_token  网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid        用户的唯一标识
     * @param string $lang          返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return array
     */
    public function getUserInfo($access_token, $openid, $lang = 'zh_CN'){
        return HttpClient::create()->get("sns/userinfo?access_token={$access_token}&openid={$openid}&lang={$lang}")->toArray();
    }

}