<?php
namespace mall\library\wechat\chat\payment;

use mall\library\wechat\chat\BasicWePay;
use mall\library\wechat\chat\lib\Prpcrypt;

class Refund extends BasicWePay {

    /**
     * 创建退款订单
     * @param array $options
     * @return array
     */
    public function create(array $options){
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        return $this->callPost($url, $options, true);
    }

    /**
     * 查询退款
     * @param array $options
     * @return array
     */
    public function query(array $options){
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        return $this->callPost($url, $options);
    }

    /**
     * 获取退款通知
     * @return array
     */
    public function getNotify(){
        $data = $this->xml2arr(file_get_contents("php://input"));
        if (!isset($data['return_code']) || $data['return_code'] !== 'SUCCESS') {
            throw new \Exception('获取退款通知XML失败！');
        }

        $pc = new Prpcrypt(md5($this->config["mch_key"]));
        $array = $pc->decrypt(base64_decode($data['req_info']));
        if (intval($array[0]) > 0) {
            throw new \Exception($array[1], $array[0]);
        }

        $data['decode'] = $array[1];
        return $data;
    }

}