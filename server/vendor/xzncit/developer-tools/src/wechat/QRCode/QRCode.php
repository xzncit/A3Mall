<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\QRCode;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class QRCode extends App {

    /**
     * 创建二维码
     * @param string|int $scene                二维码值
     * @param int $expire_seconds   值为0时，创建永久二维码
     * @return array
     * @throws \Exception
     */
    public function create($scene, $expire_seconds = 0){
        $params = [];
        // 二维码场景类型
        if (is_numeric($scene)) {
            $data = ['action_info' => ['scene' => ['scene_id' => $scene]]];
        } else {
            $data = ['action_info' => ['scene' => ['scene_str' => $scene]]];
        }

        // 临时二维码
        if ($expire_seconds > 0) {
            $data['expire_seconds'] = $expire_seconds;
            $data['action_name'] = is_numeric($scene) ? 'QR_SCENE' : 'QR_STR_SCENE';
        } else {
            $data['action_name'] = is_numeric($scene) ? 'QR_LIMIT_SCENE' : 'QR_LIMIT_STR_SCENE';
        }

        return HttpClient::create()->postJson("cgi-bin/qrcode/create?access_token=ACCESS_TOKEN",$params)->toArray();
    }

    /**
     * 通过ticket换取二维码
     * ticket正确情况下，http 返回码是200，是一张图片，可以直接展示或者下载。
     * @param string $ticket   TICKET记得进行UrlEncode
     * @return array
     * @throws \Exception
     */
    public function getQRCode($ticket){
        return HttpClient::create()->get("cgi-bin/showqrcode",[
            "ticket"=>$ticket
        ])->toArray();
    }

    /**
     * 将一条长链接转成短链接
     * 主要使用场景： 开发者用于生成二维码的原链接（商品、支付二维码等）太长导致扫码速度和成功率下降，将原长链接通过此接口转成短链接再生成二维码将大大提升扫码速度和成功率。
     * @param string $longUrl      需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url
     * @param string $action       此处填long2short，代表长链接转短链接
     * @return array
     * @throws \Exception
     */
    public function shorturl($longUrl,$action="long2short"){
        return HttpClient::create()->postJson("cgi-bin/shorturl?access_token=ACCESS_TOKEN",[
            "action"=>$action,"long_url"=>$longUrl
        ])->toArray();
    }



}