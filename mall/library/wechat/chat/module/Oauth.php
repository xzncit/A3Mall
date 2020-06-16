<?php
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Oauth extends BasicWeChat{

    /**
     * Oauth 授权跳转接口
     * @param string $redirect_url 授权回跳地址
     * @param string $state 为重定向后会带上state参数（填写a-zA-Z0-9的参数值，最多128字节）
     * @param string $scope 授权类类型(可选值snsapi_base|snsapi_userinfo)
     * @return string
     */
    public function getOauthRedirect($redirect_url, $state = '', $scope = 'snsapi_base'){
        $appid = $this->config["appid"];
        $redirect_uri = urlencode($redirect_url);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
    }

    /**
     * 通过 code 获取 AccessToken 和 openid
     * @return bool|array
     */
    public function getOauthAccessToken(){
        $appid = $this->config->get('appid');
        $appsecret = $this->config->get('appsecret');
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
        return $this->httpGet($url);
    }

    /**
     * 刷新AccessToken并续期
     * @param string $refresh_token
     * @return bool|array
     */
    public function getOauthRefreshToken($refresh_token){
        $appid = $this->config->get('appid');
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$appid}&grant_type=refresh_token&refresh_token={$refresh_token}";
        return $this->httpGet($url);
    }

    /**
     * 检验授权凭证（access_token）是否有效
     * @param string $access_token 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户的唯一标识
     * @return array
     */
    public function checkOauthAccessToken($access_token, $openid){
        $url = "https://api.weixin.qq.com/sns/auth?access_token={$access_token}&openid={$openid}";
        return $this->httpGet($url);
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param string $access_token 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * @param string $openid 用户的唯一标识
     * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return array
     */
    public function getUserInfo($access_token, $openid, $lang = 'zh_CN'){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang={$lang}";
        return $this->httpGet($url);
    }

}