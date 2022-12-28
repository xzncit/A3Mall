<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\mini\Live\Subscribe;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * 长期订阅群发接口
 * Class Subscribe
 * @package xzncit\mini\Live\Subscribe
 * @link https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/liveplayer/subscribe-api.html
 */
class Subscribe extends App {

    /**
     * 获取长期订阅用户
     * 调用此接口获取长期订阅用户列表
     * @param int $limit        获取长期订阅用户的个数限制，默认200，最大2000
     * @param int $page_break   翻页标记，获取第一页时不带，第二页开始需带上上一页返回结果中的page_break
     * @return array
     * @throws \Exception
     */
    public function getFollowers($limit=200,$page_break=0){
        return HttpClient::create()->postJson("wxa/business/get_wxa_followers?access_token=ACCESS_TOKEN",[
            "limit"=>$limit,"page_break"=>$page_break
        ])->toArray();
    }

    /**
     * 向长期订阅用户群发直播间开始事件
     * @param $room_id          直播开始事件的房间ID
     * @param $user_openid      接收该群发开播事件的订阅用户OpenId列表
     * @return array
     * @throws \Exception
     */
    public function pushMessage($room_id,$user_openid){
        return HttpClient::create()->postJson("wxa/business/push_message?access_token=ACCESS_TOKEN",[
            "room_id"=>$room_id,"user_openid"=>$user_openid
        ])->toArray();
    }

}