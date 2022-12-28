<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\QRCode;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class QRCode extends App {

    /**
     * wxacode.createQRCode
     * 获取小程序二维码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制，详见获取二维码。
     * @param $path     扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * @param $width    二维码的宽度，单位 px。最小 280px，最大 1280px
     * @return mixed
     * @throws \Exception
     */
    public function create($path,$width){
        return HttpClient::create()->postJson("cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN",[
            "path"=>$path,"width"=>$width
        ])->getResponse();
    }

    /**
     * wxacode.get
     * 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制，详见获取二维码。
     * @param $path                 扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * @param int $width            二维码的宽度，单位 px。最小 280px，最大 1280px
     * @param false $auto_color     自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
     * @param string $line_color    auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param false $is_hyaline     是否需要透明底色，为 true 时，生成透明底色的小程序码
     * @return mixed
     * @throws \Exception
     */
    public function get($path,$width=430,$auto_color=false,$line_color='{"r":0,"g":0,"b":0}',$is_hyaline=false){
        return HttpClient::create()->postJson("wxa/getwxacode?access_token=ACCESS_TOKEN",[
            "path"=>$path,"width"=>$width,"auto_color"=>$auto_color,"line_color"=>$line_color,
            "is_hyaline"=>$is_hyaline
        ])->getResponse();
    }

    /**
     * wxacode.getUnlimited
     * 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制。
     * @param string $scene         最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
     * @param string $page          必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * @param int $width            二维码的宽度，单位 px，最小 280px，最大 1280px
     * @param false $auto_color     自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
     * @param string $line_color    auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param false $is_hyaline     是否需要透明底色，为 true 时，生成透明底色的小程序
     * @return mixed
     * @throws \Exception
     */
    public function getUnlimited(string $scene,$page='pages/index/index',$width=430,$auto_color=false,$line_color=["r"=>0,"g"=>0,"b"=>0],$is_hyaline=false){
        return HttpClient::create()->postJson("wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN",[
            "scene"=>$scene,"page"=>$page,"width"=>$width,"auto_color"=>$auto_color,
            "line_color"=>$line_color,"is_hyaline"=>$is_hyaline
        ])->getResponse();
    }

}