<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Card\DeveloperMode;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class DeveloperMode extends App {

    /**
     * 创建子商户卡券接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createCard(array $data){
        return HttpClient::create()->postJson("card/create?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 创建子商户接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function create(array $data){
        return HttpClient::create()->postJson("card/submerchant/submit?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 卡券开放类目查询接口
     * @return array
     * @throws \Exception
     */
    public function getApplyProtocol(){
        return HttpClient::create()->get("card/getapplyprotocol?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 更新子商户接口
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateMerchantInfo(array $data){
        return HttpClient::create()->postJson("card/submerchant/update?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 拉取单个子商户信息接口
     * 通过指定的子商户appid，拉取该子商户的基础信息。 注意，用母商户去调用接口，但接口内传入的是子商户的appid
     * @param $merchant_id
     * @return array
     * @throws \Exception
     */
    public function getMerchantInfo($merchant_id){
        return HttpClient::create()->postJson("card/submerchant/get?access_token=ACCESS_TOKEN",[
            "merchant_id"=>$merchant_id
        ])->toArray();
    }

    /**
     * 批量拉取子商户信息接口
     * @param $begin_id     起始的子商户id，一个母商户公众号下唯一
     * @param $limit        拉取的子商户的个数，最大值为100
     * @param $status       子商户审核状态，填入后，只会拉出当前状态的子商户
     * @return array
     * @throws \Exception
     */
    public function getMerchantList($begin_id,$limit,$status){
        return HttpClient::create()->postJson("card/submerchant/batchget?access_token=ACCESS_TOKEN",[
            "begin_id"=>$begin_id,"limit"=>$limit,"status"=>$status
        ])->toArray();
    }

}