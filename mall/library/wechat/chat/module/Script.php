<?php
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;
use mall\library\wechat\chat\lib\Utils;
use think\facade\Cache;

class Script extends BasicWeChat{

    /**
     * 删除JSAPI授权TICKET
     * @param string $type TICKET类型(wx_card|jsapi)
     * @param string $appid 强制指定有效APPID
     * @return void
     */
    public function delTicket($type = 'jsapi', $appid = null){
        is_null($appid) && $appid = $this->config["appid"];
        $cache_name = "{$appid}_ticket_{$type}";
        Cache::delete($cache_name);
    }

    /**
     * 获取JSAPI_TICKET接口
     * @param string $type TICKET类型(wx_card|jsapi)
     * @param string $appid 强制指定有效APPID
     * @return string
     */
    public function getTicket($type = 'jsapi', $appid = null){
        is_null($appid) && $appid = $this->config["appid"];
        $cache_name = "{$appid}_ticket_{$type}";
        $ticket = Cache::get($cache_name);
        if (empty($ticket)) {
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type={$type}";
            $result = $this->httpGet($url);
            if (empty($result['ticket'])) {
                throw new \Exception('Invalid Resoponse Ticket.', '0');
            }
            $ticket = $result['ticket'];
            Cache::set($cache_name, $ticket, 5000);
        }
        return $ticket;
    }

    /**
     * 获取JsApi使用签名
     * @param string $url 网页的URL
     * @param string $appid 用于多个appid时使用(可空)
     * @param string $ticket 强制指定ticket
     * @return array
     */
    public function getJsSign($url, $appid = null, $ticket = null){
        list($url,) = explode('#', $url);
        is_null($ticket) && $ticket = $this->getTicket('jsapi');
        is_null($appid) && $appid = $this->config["appid"];
        $data = ["url" => $url, "timestamp" => '' . time(), "jsapi_ticket" => $ticket, "noncestr" => Utils::createRandString(16)];
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