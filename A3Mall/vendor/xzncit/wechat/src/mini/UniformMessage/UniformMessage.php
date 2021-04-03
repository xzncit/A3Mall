<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\UniformMessage;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class UniformMessage extends App {

    /**
     * uniformMessage.send
     * 下发小程序和公众号统一的服务消息
     * @param $touser               用户openid，可以是小程序的openid，也可以是mp_template_msg.appid对应的公众号的openid
     * @param $weapp_template_msg   小程序模板消息相关的信息，可以参考小程序模板消息接口; 有此节点则优先发送小程序模板消息
     * @param $mp_template_msg      公众号模板消息相关的信息，可以参考公众号模板消息接口；有此节点并且没有weapp_template_msg节点时，发送公众号模板消息
     * @return array
     * @throws \Exception
     */
    public function send($touser,$weapp_template_msg,$mp_template_msg){
        return HttpClient::create()->postJson("cgi-bin/message/wxopen/template/uniform_send?access_token=ACCESS_TOKEN",[
            "touser"=>$touser,"weapp_template_msg"=>$weapp_template_msg,
            "mp_template_msg"=>$mp_template_msg
        ])->toArray();
    }

}