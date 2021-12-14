<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\library\wechat;

use xzncit\Factory as WechatFactory;
use app\common\models\Setting as SettingModel;

class Factory {

    /**
     * init wechat
     * @param array $config
     * @return \xzncit\wechat\Wechat
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function wechat(array $config = []){
        if(empty($config)){
            $config = SettingModel::getArrayData("wechat");
        }

        return WechatFactory::Wechat([
            "token"     => trim($config["token"]),
            "appid"     => trim($config["appid"]),
            "appsecret" => trim($config["appsecret"]),
            "enaeskey"  => trim($config["enaes_key"])
        ]);
    }

}