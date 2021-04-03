<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\ServiceMarket;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class ServiceMarket extends App {

    /**
     * serviceMarket.invokeService
     * 调用服务平台提供的服务
     * @param $service          服务 ID
     * @param $api              接口名
     * @param $data             服务提供方接口定义的 JSON 格式的数据
     * @param $client_msg_id    随机字符串 ID，调用方请求的唯一标识
     * @return array
     * @throws \Exception
     */
    public function invokeService($service,$api,$data,$client_msg_id){
        return HttpClient::create()->postJson("wxa/servicemarket?access_token=ACCESS_TOKEN",[
            "service"=>$service,"api"=>$api,"data"=>$data,"client_msg_id"=>$client_msg_id
        ])->toArray();
    }

}