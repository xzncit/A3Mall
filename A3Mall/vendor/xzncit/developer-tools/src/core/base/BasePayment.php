<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\base;

use xzncit\core\App;
use xzncit\core\http\HttpClient;
use xzncit\core\http\Response;
use xzncit\core\Utils;

class BasePayment extends App {

    /**
     * 生成支付签名
     * @param array $data      参与签名的数据
     * @param string $signType 参与签名的类型
     * @param string $buff     参与签名字符串前缀
     * @return string
     */
    public function getPaySign(array $data, $signType = 'MD5', $buff = ''){
        ksort($data);

        if (isset($data['sign'])) {
            unset($data['sign']);
        }

        foreach ($data as $k => $v) {
            $buff .= "{$k}={$v}&";
        }

        $buff .= ("key=" . $this->app->config["mch_key"]);
        if (strtoupper($signType) === 'MD5') {
            return strtoupper(md5($buff));
        }

        return strtoupper(hash_hmac('SHA256', $buff, $this->app->config["mch_key"]));
    }

    /**
     * @param string $url           请求地址
     * @param array $data           请求参数
     * @param false $isCert         是否需要使用双向证书
     * @param string $signType      数据签名类型(MD5|SHA256)
     * @param bool $needSignType    是否需要传签名类型参数
     * @return array
     * @throws \Exception
     */
    protected function request($url, array $data, $isCert = false, $signType = "HMAC-SHA256", $needSignType = true){
        $options = [];
        if($isCert){
            $options["cert"]    = $this->app->config["ssl_cer"];
            $options["ssl_key"] = $this->app->config["ssl_key"];
        }

        $params = array_merge([
            "appid"     =>  $this->app->config["appid"],
            "mch_id"    =>  $this->app->config["mch_id"],
            "nonce_str" =>  Utils::getRandString()
        ],$data);

        if($needSignType){
            $params['sign_type'] = strtoupper($signType);
        }

        $params['sign'] = $this->getPaySign($params, $signType);
        $result = HttpClient::create()->post($url,Response::arr2xml($params),$options)->toArray();
        if ($result['return_code'] !== 'SUCCESS') {
            throw new \Exception($result['return_msg'],0);
        }

        return $result;
    }

}