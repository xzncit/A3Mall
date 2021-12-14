<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\wechat\Template;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Template extends App {

    /**
     * 发送模板消息
     * 注意: URL置空，则在发送后，点击模版消息会进入一个空白页面（ios），或无法点击（android）。
     * @param $openid
     * @param $template_id
     * @param $url
     * @param $data
     * @param $topcolor
     * @return array
     * @throws \Exception
     */
    public function send($openid,$template_id,$url,$data,$topcolor){
        $params = [
            "touser"=>$openid,
            "template_id"=>$template_id,
            "url"=>$url,
            "topcolor"=>$topcolor,
            "data"=>$data
        ];
        return HttpClient::create()->postJson("cgi-bin/message/template/send?access_token=ACCESS_TOKEN",$params)->toArray();
    }

}