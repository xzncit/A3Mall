<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\base;

use xzncit\core\Cache;
use xzncit\core\Config;
use xzncit\core\http\HttpClient;

class AccessToken {

    private static $cacheName = "access_token";

    public static function get(){
        $cache = Cache::create();
        $cacheName = self::getCacheName();
        if($cache->has($cacheName)){
            return $cache->get($cacheName);
        }

        $data = self::set();
        $cache->set($cacheName,$data["access_token"],7000);
        return $data["access_token"];
    }

    public static function set(){
        return self::getRequestData();
    }

    public static function delete(){
        Cache::create()->delete(self::getCacheName());
    }

    private static function getRequestData(){
        $config = Config::get();
        switch($config["mode"]){
            case "wechat":
                return HttpClient::create()->get("cgi-bin/token",[
                    "grant_type"=>"client_credential",
                    "appid"=>$config["appid"],
                    "secret"=>$config["appsecret"],
                ])->toArray();
            case "qq":
                $res = HttpClient::create()->get("api/getToken",[
                    "grant_type"=>"client_credential",
                    "appid"=>$config["appid"],
                    "secret"=>$config["appsecret"],
                ])->toArray();

                if(isset($res["errcode"]) && $res["errcode"] != 0){
                    throw new \Exception($res["errmsg"].":".$res["err_no"],0);
                }

                return [
                    "access_token"=>$res["access_token"],
                    "expires_in"=>$res["expires_in"]
                ];
            case "microapp":
                $res = HttpClient::create()->postJson("api/apps/v2/token",[
                    "grant_type"=>"client_credential",
                    "appid"=>$config["appid"],
                    "secret"=>$config["appsecret"],
                ])->toArray();

                if($res["err_no"] == 0 && $res["err_tips"] == "success"){
                    return [
                        "access_token"=>$res["data"]["access_token"],
                        "expires_in"=>$res["data"]["expires_in"]
                    ];
                }

                throw new \Exception($res["err_tips"].":".$res["err_no"],0);
        }
    }

    private static function getCacheName(){
        return self::$cacheName . "_" . Config::get("appid");
    }

}