<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Security;


use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class Security extends App {

    /**
     * 检测一段文本是否包含违法违规内容
     * @param array $tasks      检测任务列表
     * @param string $content   检测的文本内容
     * @return array
     * @throws \Exception
     */
    public function content($tasks,$content){
        return HttpClient::create()->postJson("api/v2/tags/text/antidirt",[
            "tasks"=>$tasks,
            "content"=>$content
        ],[
            "X-Token"=>AccessToken::get()
        ])->toArray();
    }

    /**
     * 检测图片是否包含违法违规内容
     * @param $params [
     *      app_id          => 小程序 ID
     *      image           => 检测的图片链接
     *      image_data      => 图片数据的 base64 格式，有 image 字段时，此字段无效
     * ]
     * @return array
     * @throws \Exception
     *
     * tips: image 和 image_data 至少存在一个，同时存在时 image_data 无效
     * tips: 请求 body 的Content-Type限定为application/json
     * tips: 该接口请在开发者服务器端请求
     */
    public function image($params){
        $params["access_token"] = AccessToken::get();
        return HttpClient::create()->postJson("api/apps/censor/image",$params)->toArray();
    }


}