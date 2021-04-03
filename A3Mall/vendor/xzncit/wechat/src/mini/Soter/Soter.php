<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Soter;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Soter extends App {

    /**
     * soter.verifySignature
     * SOTER 生物认证秘钥签名验证
     * @param $openid           用户 openid
     * @param $json_string      通过 wx.startSoterAuthentication 成功回调获得的 resultJSON 字段
     * @param $json_signature   通过 wx.startSoterAuthentication 成功回调获得的 resultJSONSignature 字段
     * @return array
     * @throws \Exception
     */
    public function verifySignature($openid,$json_string,$json_signature){
        return HttpClient::create()->postJson("cgi-bin/soter/verify_signature?access_token=ACCESS_TOKEN",[
            "openid"=>$openid,"json_string"=>$json_string,"json_signature"=>$json_signature
        ])->toArray();
    }

}