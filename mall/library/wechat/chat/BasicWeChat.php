<?php
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