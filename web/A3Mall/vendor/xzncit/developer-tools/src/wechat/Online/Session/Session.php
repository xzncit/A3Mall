<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Online\Session;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 客服消息 - 会话控制
 * Class Session
 * @package xzncit\wechat\Online\Session
 */
class Session extends App {

    /**
     * 创建会话
     * 此接口在客服和用户之间创建一个会话，如果该客服和用户会话已存在，则直接返回0。
     * 指定的客服帐号必须已经绑定微信号且在线。
     * @param string $kfAccount     完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param string $openid        粉丝的openid
     * @return array
     * @throws \Exception
     */
    public function create($kfAccount,$openid){
        return HttpClient::create()->postJson("customservice/kfsession/create?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount,"openid"=>$openid
        ])->toArray();
    }

    /**
     * 关闭会话
     * @param $kfAccount    完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param $openid       粉丝的openid
     * @return array
     * @throws \Exception
     */
    public function close($kfAccount,$openid){
        return HttpClient::create()->postJson("customservice/kfsession/close?access_token=ACCESS_TOKEN",[
            "kf_account"=>$kfAccount,"openid"=>$openid
        ])->toArray();
    }

    /**
     * 获取客户会话状态
     * @param $openid   粉丝的openid
     * @return array
     * @throws \Exception
     */
    public function status($openid){
        return HttpClient::create()->get("customservice/kfsession/getsession?access_token=ACCESS_TOKEN&openid={$openid}")->toArray();
    }

    /**
     * 获取客服会话列表
     * @param $kfAccount    完整客服帐号，格式为：帐号前缀@公众号微信号
     * @return array
     * @throws \Exception
     */
    public function getList($kfAccount){
        return HttpClient::create()->get("customservice/kfsession/getsessionlist?access_token=ACCESS_TOKEN&kf_account={$kfAccount}")->toArray();
    }

    /**
     * 获取未接入会话列表
     * @return array
     * @throws \Exception
     */
    public function getWaitCase(){
        return HttpClient::create()->get("customservice/kfsession/getwaitcase?access_token=ACCESS_TOKEN")->toArray();
    }
}