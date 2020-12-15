<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\library\wechat\mini\module;

use mall\library\wechat\mini\BasicWeMini;

class QRCode extends BasicWeMini{

    /**
     * 获取小程序二维码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * @param $data [
     * "path"=>"", // 扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * "width"=>"" // 二维码的宽度，单位 px。最小 280px，最大 1280px
     * ]
     * @return mixed 返回图片的Buffer
     * @throws \Exception
     */
    public function createQRCode($data){
        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN";
        return $this->post($url, $this->arr2json($data));
    }

    /**
     * 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function wxacodeGet($data){
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN";
        return $this->post($url, $this->arr2json($data));
    }

    /**
     * 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * @link https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function getUnlimited($data){
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN";
        return $this->post($url, $this->arr2json($data));
    }

}