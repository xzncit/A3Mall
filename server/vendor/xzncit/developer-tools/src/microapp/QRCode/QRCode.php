<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\QRCode;

use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class QRCode extends App {

    /**
     * 生成二维码
     * 获取小程序/小游戏的二维码。该二维码可通过任意 app 扫码打开，能跳转到开发者指定的对应字节系
     * app 内拉起小程序/小游戏， 并传入开发者指定的参数。通过该接口生成的二维码，永久有效，暂无数量限制。
     * @param array $params [
     *      "appname"=>"douyin",                      // 打开二维码的字节系 app 名称，默认为今日头条 "toutiao"=>"今日头条" "douyin"=>"抖音" "pipixia"=>"皮皮虾" "huoshan"=>"火山小视频"
     *      "path"=>"",                               // 小程序/小游戏启动参数，小程序则格式为 encode({path}?{query})，小游戏则格式为 JSON 字符串，默认为空
     *      "width"=>"",                              // 二维码宽度，单位 px，最小 280px，最大 1280px，默认为 430px
     *      "line_color"=>"{"r":0,"g":0,"b":0}",      // 二维码线条颜色，默认为黑色
     *      "background"=>"",                         // 二维码背景颜色，默认为白色
     *      "set_icon"=>false                         // 是否展示小程序/小游戏 icon，默认不展示
     * ]
     * @return Buffer
     * @throws \Exception
     */
    public function create($params=[]){
        $params["access_token"] = AccessToken::get();
        return HttpClient::create()->postJson("api/apps/qrcode",$params)->getResponse();
    }

}