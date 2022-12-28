<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\qq\QRCode;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class QRCode extends App {

    /**
     * 获取小程序码
     * @param $path     扫码进入的小程序页面路径，小程序不填进入首页，小游戏无需填写
     * @return array
     * @throws \Exception
     */
    public function create($path){
        return HttpClient::create()->postJson("api/json/qqa/CreateMiniCode?access_token=ACCESS_TOKEN",[
            "path"=>$path,"appid"=>$this->app->config["appid"]
        ])->toArray();
    }

}