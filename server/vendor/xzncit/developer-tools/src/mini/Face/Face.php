<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Face;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Face extends App {

    /**
     * 人脸核身之后，开发者可以根据jsapi返回的verify_result向后台拉取当次认证的结果信息。
     * @param $verifyResult     jsapi返回的加密key（凭据）
     * @return array
     * @throws \Exception
     */
    public function identify($verifyResult){
        return HttpClient::create()->postJson("cityservice/face/identify/getinfo?access_token=ACCESS_TOKEN",[
            "verifyResult"=>$verifyResult
        ])->toArray();
    }

}