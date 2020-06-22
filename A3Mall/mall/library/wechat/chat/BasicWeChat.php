<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat;

use mall\library\wechat\chat\WeConfig;

class BasicWeChat extends CommonWeChat {

    public function __construct(){
        $this->config = WeConfig::get("wechat");
        $this->request = $_REQUEST;

        if(empty($this->config["appid"])){
            throw new \Exception("公众号 AppId 为空",0);
        }

        if(empty($this->config["appsecret"])){
            throw new \Exception("公众号 AppSecret 为空",0);
        }

    }


}