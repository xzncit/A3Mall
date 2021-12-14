<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Storage;

use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class Storage extends App {

    /**
     * 设置数据缓存
     * 以 key-value 形式存储用户数据到小程序平台的云存储服务。若开发者无内部存储服务则可接入，免费且无需申请。一般情况下只存储用户的基本信息，禁止写入大量不相干信息。
     * @param $openid           登录用户唯一标识
     * @param $signature        用户登录态签名
     * @param $sig_method       用户登录态签名的编码方法
     * @param $kv_list          (body 中) 要设置的用户数据
     * @return array
     * @throws \Exception
     */
    public function set($openid,$signature,$sig_method,$kv_list){
        return HttpClient::create()->postJson("api/apps/set_user_storage",[
            "access_token"=>AccessToken::get(),
            "openid"=>$openid,
            "signature"=>$signature,
            "sig_method"=>$sig_method,
            "kv_list"=>$kv_list
        ])->toArray();
    }


    public function remove($openid,$signature,$sig_method,$key){
        return HttpClient::create()->postJson("api/apps/remove_user_storage",[
            "access_token"=>AccessToken::get(),
            "openid"=>$openid,
            "signature"=>$signature,
            "sig_method"=>$sig_method,
            "key"=>$key
        ])->toArray();
    }

}