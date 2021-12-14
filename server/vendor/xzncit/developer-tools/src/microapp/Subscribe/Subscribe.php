<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Subscribe;


use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class Subscribe extends App {

    /**
     * 发送模板消息给用户
     * @param string $appid        小程序的 id
     * @param string $tpl_id       模板的 id
     * @param string $open_id      接收消息目标用户的 open_id
     * @param array  $data         模板内容，格式形如 ["key1"=>"value1", "key2"=>"value2"]
     * @param string $page  跳转的页面
     * @return array
     * @throws \Exception
     * Tips: 对单个用户推送消息，频率限制为 1 次/秒
     * Tips: 订阅消息分为一次性订阅和长期订阅
     */
    public function send($appid,$tpl_id,$open_id,$data,$page=""){
        return HttpClient::create()->postJson("api/apps/subscribe_notification/developer/v1/notify",[
            "access_token"=>AccessToken::get(),
            "app_id"=>$appid,"tpl_id"=>$tpl_id,"open_id"=>$open_id,
            "data"=>$data,"page"=>$page
        ])->toArray();
    }

}