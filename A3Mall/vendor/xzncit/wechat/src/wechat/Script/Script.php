<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Script;

use xzncit\core\App;
use xzncit\core\http\HttpClient;
use xzncit\core\Cache;

class Script extends App {

    /**
     * 删除JSAPI授权TICKET
     * @param string $type  TICKET类型(wx_card|jsapi)
     * @return void
     */
    public function deleteTicket($type = 'jsapi'){
        $appid = $this->app->config["appid"];
        Cache::delete("{$appid}_ticket_{$type}");
    }

    /**
     * 获取JSAPI_TICKET接口
     * @param string $type TICKET类型(wx_card|jsapi)
     * @return string
     */
    public function getTicket($type = 'jsapi'){
        $appid = $this->app->config["appid"];
        $cacheName = "{$appid}_ticket_{$type}";
        $ticket = Cache::get($cacheName);
        if (!empty($ticket)) {
            return $ticket;
        }

        $result = HttpClient::create()->get("cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type={$type}")->toArray();
        if (empty($result['ticket'])) {
            throw new \Exception("Invalid Resoponse Ticket.", '0');
        }

        $ticket = $result['ticket'];
        Cache::set($cacheName, $ticket, 5000);
        return $ticket;
    }

    /**
     * 获取JsApi使用签名
     * @param string $url 网页的URL
     * @param string $ticket 强制指定ticket
     * @return array
     */
    public function getJsSign($url, $ticket = null){
        list($url,) = explode('#', $url);

        if(is_null($ticket)){
            $ticket = $this->getTicket('jsapi');
        }

        $appid = $this->app->config["appid"];
        $data = [
            "url" => $url,
            "timestamp" => '' . time(),
            "jsapi_ticket" => $ticket,
            "noncestr" => Utils::getRandString(16)
        ];

        return [
            'debug'     => false,
            "appId"     => $appid,
            "nonceStr"  => $data['noncestr'],
            "timestamp" => $data['timestamp'],
            "signature" => $this->getSignature($data, 'sha1'),
            'jsApiList' => [
                'updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareTimeline',
                'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone',
                'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice',
                'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice',
                'chooseImage', 'previewImage', 'uploadImage', 'downloadImage',
                'translateVoice', 'getNetworkType', 'openLocation', 'getLocation',
                'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems',
                'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem',
                'closeWindow', 'scanQRCode', 'chooseWXPay', 'openProductSpecificView',
                'addCard', 'chooseCard', 'openCard',
            ],
        ];
    }

    /**
     * 数据生成签名
     * @param array $data 签名数组
     * @param string $method 签名方法
     * @param array $params 签名参数
     * @return bool|string 签名值
     */
    protected function getSignature($data, $method = "sha1", $params = []){
        ksort($data);
        if (!function_exists($method)) {
            return false;
        }

        foreach ($data as $k => $v) {
            array_push($params, "{$k}={$v}");
        }

        return $method(join('&', $params));
    }


}