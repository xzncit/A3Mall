<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\mini\module;

use mall\library\wechat\mini\BasicWeMini;

class Oauth extends BasicWeMini {

    /**
     * 登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录流程。
     * 更多使用方法详见 小程序登录。
     * return [
     *  openid,       用户唯一标识
     *  session_key,  会话密钥
     *  unionid,      用户在开放平台的唯一标识符，在满足 UnionID 下发条件的情况下会返回，详见 UnionID 机制说明。
     *  errcode,      错误码
     *  errmsg        错误信息
     * ]
     */
    public function getAuthCode2Session($code){
        $appid = $this->config['appid'];
        $appsecret = $this->config['appsecret'];
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appsecret}&js_code={$code}&grant_type=authorization_code";
        return $this->httpGet($url);
    }

    /**
     * 用户支付完成后，获取该用户的 UnionId，无需用户授权
     * 注意：调用前需要用户完成支付，且在支付后的五分钟内有效。
     * @param $openid
     * @return mixed
     * @throws \Exception
     */
    public function getAuthGetPaidUnionId($openid,$transaction_id){
        $url = "https://api.weixin.qq.com/wxa/getpaidunionid?access_token=ACCESS_TOKEN&openid={$openid}&transaction_id={$transaction_id}";
        return $this->httpGet($url);
    }


}