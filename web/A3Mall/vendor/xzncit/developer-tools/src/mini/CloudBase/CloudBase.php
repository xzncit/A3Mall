<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\CloudBase;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class CloudBase extends App {

    /**
     * cloudbase.getOpenData
     * 换取 cloudID 对应的开放数据
     * @param $cloudid_list     CloudID 列表
     * @param $openid           OpenID
     * @return array
     * @throws \Exception
     */
    public function addUserAction($cloudid_list,$openid){
        return HttpClient::create()->postJson("wxa/getopendata?openid={$openid}&access_token=ACCESS_TOKEN",[
            "cloudid_list"=>$cloudid_list
        ])->toArray();
    }

    /**
     * cloudbase.getVoIPSign
     * 获取实时语音签名
     * @param $openid       OpenID
     * @param $group_id     游戏房间的标识
     * @param $nonce        随机字符串，长度应小于 128
     * @param $timestamp    生成这个随机字符串的 UNIX 时间戳（精确到秒）
     * @return array
     * @throws \Exception
     */
    public function getVoIPSign($openid,$group_id,$nonce,$timestamp){
        return HttpClient::create()->postJson("wxa/getvoipsign?openid={$openid}&access_token=ACCESS_TOKEN",[
            "group_id"=>$group_id,"nonce"=>$nonce,"timestamp"=>$timestamp
        ])->toArray();
    }

    /**
     * cloudbase.sendSms
     * 发送支持打开云开发静态网站的短信，该 H5 可以打开小程序。
     * @param $env                  环境 ID
     * @param $phone_number_list    手机号列表，单次请求最多支持 1000 个境内手机号，手机号必须以+86开头
     * @param $content              自定义短信内容，最长支持 30 个字符
     * @param $path                 云开发静态网站 path，不需要指定域名，例如/index.html
     * @return array
     * @throws \Exception
     */
    public function sendSms($env,$phone_number_list,$content,$path){
        return HttpClient::create()->postJson("sendsms?access_token=ACCESS_TOKEN",[
            "env"=>$env,"phone_number_list"=>$phone_number_list,
            "content"=>$content,"path"=>$path
        ])->toArray();
    }

}