<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\basic;

use mall\utils\HttpClient;
use mall\utils\Tool;
use think\facade\Config;

class Upgrade {

    private $api_url = "http://api.a3-mall.com/";
    private $cert = null;
    private $token = null;

    public function __construct(){
        $cert = Tool::getRootPath() . "cert.key";
        if(file_exists($cert)){
            $this->cert = file_get_contents($cert);
        }
    }

    public function getInfo(){
        if(empty($this->cert)){
            throw new \Exception("授权文件不存在",0);
        }

        $json = HttpClient::post($this->api_url . 'auth/info',[
            "cert"=>$this->cert
        ]);

        return json_decode($json,true);
    }

    public function getUpdateList(){
        if(empty($this->cert)){
            throw new \Exception("授权文件不存在",0);
        }

        $json = HttpClient::post($this->api_url . 'auth/update',[
            "cert"=>$this->cert,
            "version"=>Config::get("version.version"),
            "version_mini"=>Config::get("version.version_mini"),
            "version_pc"=>Config::get("version.version_pc"),
            "version_app"=>Config::get("version.version_app"),
            "code"=>Config::get("version.code"),
        ]);

        return json_decode($json,true);
    }
}