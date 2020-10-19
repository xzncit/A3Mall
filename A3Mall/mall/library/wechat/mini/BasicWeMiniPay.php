<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\mini;

use mall\library\wechat\chat\CommonWeChat;
use mall\library\wechat\mini\lib\Utils;
use mall\utils\Tool;
use mall\library\wechat\chat\WeConfig;

class BasicWePay extends CommonWeChat {

    /**
     * 当前请求数据
     */
    protected $params;
    protected static $cache;

    public function __construct(){
        $this->config = WeConfig::get("wemini");

        if(empty($this->config["mch_id"])){
            throw new \Exception("支付 mch_id 为空",0);
        }

        if(empty($this->config["mch_key"])){
            throw new \Exception("支付 mch_key 为空",0);
        }

        $this->params = [
            'appid'     => $this->config["appid"],
            'mch_id'    => $this->config["mch_id"],
            'nonce_str' => Utils::createRandString()
        ];
    }

    /**
     * 静态创建对象
     * @param array $config
     * @return static
     */
    public static function instance()
    {
        $key = md5(get_called_class());
        if (isset(self::$cache[$key])) return self::$cache[$key];
        return self::$cache[$key] = new static();
    }

    /**
     * 获取微信支付通知
     * @return array
     */
    public function getNotify(){
        $data = $this->xml2arr(file_get_contents('php://input'));
        if (isset($data['sign']) && $this->getPaySign($data) === $data['sign']) {
            return $data;
        }
        throw new \Exception('Invalid Notify.', '0');
    }

    /**
     * 获取微信支付通知回复内容
     * @return string
     */
    public function getNotifySuccessReply(){
        return $this->arr2xml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']);
    }

    /**
     * 生成支付签名
     * @param array $data 参与签名的数据
     * @param string $signType 参与签名的类型
     * @param string $buff 参与签名字符串前缀
     * @return string
     */
    public function getPaySign(array $data, $signType = 'MD5', $buff = ''){
        ksort($data);
        if (isset($data['sign'])) unset($data['sign']);
        foreach ($data as $k => $v) $buff .= "{$k}={$v}&";
        $buff .= ("key=" . $this->config["mch_key"]);
        if (strtoupper($signType) === 'MD5') {
            return strtoupper(md5($buff));
        }
        return strtoupper(hash_hmac('SHA256', $buff, $this->config["mch_key"]));
    }

    /**
     * 转换短链接
     * @param string $longUrl 需要转换的URL，签名用原串，传输需URLencode
     * @return array
     */
    public function shortUrl($longUrl){
        $url = 'https://api.mch.weixin.qq.com/tools/shorturl';
        return $this->callPost($url, ['long_url' => $longUrl]);
    }

    /**
     * 以Post请求接口
     * @param string $url 请求
     * @param array $data 接口参数
     * @param bool $isCert 是否需要使用双向证书
     * @param string $signType 数据签名类型 MD5|SHA256
     * @param bool $needSignType 是否需要传签名类型参数
     * @return array
     */
    protected function callPost($url,$data, $isCert = false, $signType = 'HMAC-SHA256', $needSignType = true){
        $option = [];
        if ($isCert) {
            $option['ssl_cer'] = Tool::getRootPath() . trim($this->config["cert_url"],"/");
            $option['ssl_key'] = Tool::getRootPath() . trim($this->config["key_url"],"/");
            if (empty($option['ssl_cer']) || !file_exists($option['ssl_cer'])) {
                throw new \Exception("Missing Config -- ssl_cer", '0');
            }
            if (empty($option['ssl_key']) || !file_exists($option['ssl_key'])) {
                throw new \Exception("Missing Config -- ssl_key", '0');
            }
        }

        $params = array_merge($this->params,$data);
        $needSignType && ($params['sign_type'] = strtoupper($signType));
        $params['sign'] = $this->getPaySign($params, $signType);
        $result = $this->xml2arr($this->post($url, $this->arr2xml($params), $option));
        if ($result['return_code'] !== 'SUCCESS') {
            throw new \Exception($result['return_msg'], '0');
        }

        return $result;
    }
}