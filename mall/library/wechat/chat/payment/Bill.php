<?php
namespace mall\library\wechat\chat\payment;

use mall\library\wechat\chat\BasicWePay;

class Bill extends BasicWePay{

    /**
     * 下载对账单
     * @param array $options 静音参数
     * @param null|string $outType 输出类型
     * @return bool|string
     */
    public function download(array $options, $outType = null){
        $this->params["sign_type"] = "MD5";
        $params = array_merge($this->params,$options);
        $params['sign'] = $this->getPaySign($params, 'MD5');
        $result = $this->post('https://api.mch.weixin.qq.com/pay/downloadbill', $this->arr2xml($params));
        if (($jsonData = $this->xml2arr($result))) {
            if ($jsonData['return_code'] !== 'SUCCESS') {
                throw new \Exception($jsonData['return_msg'], '0');
            }
        }
        return is_null($outType) ? $result : $outType($result);
    }


    /**
     * 拉取订单评价数据
     * @param array $options
     * @return array
     */
    public function comment(array $options){
        $url = 'https://api.mch.weixin.qq.com/billcommentsp/batchquerycomment';
        return $this->callPost($url, $options, true);
    }
}