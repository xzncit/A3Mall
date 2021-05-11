<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Base;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 微信基础工具类
 * Class Base
 * @package xzncit\wechat\Base
 */
class Base extends App {

    /**
     * 获取微信服务器IP地址
     * @return array
     */
    public function ip(){
        return HttpClient::create()->get("cgi-bin/get_api_domain_ip?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * callback IP即微信调用开发者服务器所使用的出口IP。
     * @return array
     */
    public function getCallbackIp(){
        return HttpClient::create()->get("cgi-bin/getcallbackip?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 网络检测
     * @return array
     */
    public function checkNetwork(){
        return HttpClient::create()->postJson("cgi-bin/callback/check?access_token=ACCESS_TOKEN",[
            "action"=>"all",
            "check_operator"=>"DEFAULT"
        ])->toArray();
    }

    /**
     * 公众号调用或第三方平台帮公众号调用对公众号的所有api调用（包括第三方帮其调用）次数进行清零
     * @return array
     * @throws \Exception
     */
    public function clearQuota(){
        return HttpClient::create()->postJson("cgi-bin/clear_quota?access_token=ACCESS_TOKEN",[
            "appid"=>$this->app->config["appid"]
        ])->toArray();
    }

}