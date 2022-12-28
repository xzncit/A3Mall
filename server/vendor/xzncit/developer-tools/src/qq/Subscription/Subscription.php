<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\qq\Subscription;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Subscription extends App {

    /**
     * 发送订阅消息
     * @param array $params
     * [
        属性	                类型	        必填	说明
        access_token	    string		是	接口调用凭证
        touser	            string		是	接收者（用户）的 openid
        template_id	        string		是	所需下发的订阅消息的模板id
        page	            string		否	点击订阅消息卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
        data	            Object		否	模板内容，要求字段数量和模板本身的字段数量一致。具体格式请参考示例。
        emphasis_keyword	string		否	模板需要放大的关键词，不填则默认无放大。
        oac_appid	        string		否	若希望通过小程序绑定的公众号下发，则在该字段填入公众号的 appid
        use_robot	        number		否	若希望通过客服机器人下发，则在该字段填1
     * ]
     * @return array
     * @throws \Exception
     */
    public function send($params=[]){
        return HttpClient::create()->postJson("api/json/subscribe/SendSubscriptionMessage?access_token=ACCESS_TOKEN",[
            "content"=>$params
        ])->toArray();
    }

}