<?php
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Scan extends BasicWeChat{

    /**
     * 获取商户信息
     * @return array
     */
    public function getMerchantInfo(){
        $url = "https://api.weixin.qq.com/scan/merchantinfo/get?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 创建商品
     * @param array $data
     * @return array
     */
    public function addProduct(array $data){
        $url = "https://api.weixin.qq.com/scan/product/create?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 商品发布
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @param string $status 设置发布状态。on为提交审核，off为取消发布
     * @return array
     */
    public function modProduct($keystandard, $keystr, $status = 'on'){
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr, 'status' => $status];
        $url = "https://api.weixin.qq.com/scan/product/modstatus?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 设置测试人员白名单
     * @param array $openids 测试人员的openid列表
     * @param array $usernames 测试人员的微信号列表
     * @return array
     */
    public function setTestWhiteList($openids = [], $usernames = []){
        $data = ['openid' => $openids, 'username' => $usernames];
        $url = "https://api.weixin.qq.com/scan/product/modstatus?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 获取商品二维码
     * @param string $keystandard
     * @param string $keystr
     * @param null|string $extinfo
     * @param integer $qrcode_size
     * @return array
     */
    public function getQrc($keystandard, $keystr, $extinfo = null, $qrcode_size = 64){
        $data = ['keystandard' => $keystandard, 'keystr' => $keystr, 'qrcode_size' => $qrcode_size];
        is_null($extinfo) || $data['extinfo'] = $extinfo;
        $url = "https://api.weixin.qq.com/scan/product/getqrcode?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 查询商品信息
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @return array
     */
    public function getProductInfo($keystandard, $keystr){
        $url = "https://api.weixin.qq.com/scan/product/get?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['keystandard' => $keystandard, 'keystr' => $keystr]);
    }

    /**
     * 批量查询商品信息
     * @param integer $offset 批量查询的起始位置，从0开始，包含该起始位置。
     * @param integer $limit 批量查询的数量。
     * @param string $status 支持按状态拉取。on为发布状态，off为未发布状态，check为审核中状态，reject为审核未通过状态，all为所有状态。
     * @param string $keystr 支持按部分编码内容拉取。填写该参数后，可将编码内容中包含所传参数的商品信息拉出。类似关键词搜索。
     * @return array
     */
    public function getProductList($offset = 1, $limit = 10, $status = null, $keystr = null){
        $data = ['offset' => $offset, 'limit' => $limit];
        is_null($status) || $data['status'] = $status;
        is_null($keystr) || $data['keystr'] = $keystr;
        $url = "https://api.weixin.qq.com/scan/product/getlist?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 更新商品信息
     * @param array $data
     * @return array
     */
    public function updateProduct(array $data){
        $url = "https://api.weixin.qq.com/scan/product/update?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 清除商品信息
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @return array
     */
    public function clearProduct($keystandard, $keystr){
        $url = "https://api.weixin.qq.com/scan/product/clear?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['keystandard' => $keystandard, 'keystr' => $keystr]);
    }

    /**
     * 检查wxticket参数
     * @param string $ticket
     * @return array
     */
    public function checkTicket($ticket){
        $url = "https://api.weixin.qq.com/scan/scanticket/check?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['ticket' => $ticket]);
    }

    /**
     * 清除扫码记录
     * @param string $keystandard 商品编码标准
     * @param string $keystr 商品编码内容
     * @param string $extinfo 调用“获取商品二维码接口”时传入的extinfo，为标识参数
     * @return array
     */
    public function clearScanTicket($keystandard, $keystr, $extinfo){
        $url = "https://api.weixin.qq.com/scan/scanticket/check?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['keystandard' => $keystandard, 'keystr' => $keystr, 'extinfo' => $extinfo]);
    }

}