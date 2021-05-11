<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\URLScheme;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class URLScheme extends App {

    /**
     * urlscheme.generate
     * 获取小程序scheme码，适用于短信、邮件、外部网页等拉起小程序的业务场景。通过该接口，可以选择生成到期失效和永久有效的小程序码，目前仅针对国内非个人主体的小程序开放，详见获取URL scheme码。
     * @param $jump_wxa
     * @param false $is_expire
     * @param null $expire_time
     * @return array
     * @throws \Exception
     */
    public function generate($jump_wxa,$is_expire=false,$expire_time=null){
        $data = ["jump_wxa"=>$jump_wxa,"is_expire"=>$is_expire];
        is_null($expire_time) || $data["expire_time"] = $expire_time;
        return HttpClient::create()->postJson("wxa/generatescheme?access_token=ACCESS_TOKEN",$data)->toArray();
    }


}