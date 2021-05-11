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
        $config = Config::get();
        return HttpClient::create()->get("cgi-bin/token",[
            "grant_type"=>"client_credential",
            "appid"=>$config["appid"],
            "secret"=>$config["appsecret"],
        ])->toArray();
    }

    public static function delete(){
        Cache::create()->delete(self::getCacheName());
    }

    private static function getCacheName(){
        return self::$cacheName . "_" . Config::get("appid");
    }

}