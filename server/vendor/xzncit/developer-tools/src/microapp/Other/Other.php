<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\microapp\Other;

use xzncit\core\App;
use xzncit\core\base\AccessToken;
use xzncit\core\http\HttpClient;

class Other extends App {

    /**
     * 提供拍抖音的黑白名单管理能力，开发者可以决定哪些用户拍抖音时可以挂载他们的小程序(白名单)，哪些用户拍抖音时不可以挂载他们的小程序(黑名单)。
     *
     * 小程序设置了白名单后，只有白名单内的用户才能够挂载小程序，其他用户均不可挂载；小程序设置了黑名单后，
     * 黑名单内的用户不可以挂载小程序，其他用户均可以进行挂载；如果小程序黑白名单都没有设置，
     * 那么默认所有用户都可以进行挂载小程序；如果小程序同时设置了黑白名单，以白名单为准，
     * 只有白名单内的用户才可以挂载小程序。
     * @param $appid        小程序的 appID
     * @param $uniq_id      用户抖音号
     * @param int $type     操作类型，1:黑名单增加用户 2:白名单增加用户 3:黑名单删除用户 4:白名单删除用户
     * @return array
     * @throws \Exception
     */
    public function roster($appid,$uniq_id,$type=1){
        return HttpClient::create()->postJson("api/apps/share_config",[
            "access_token"=>AccessToken::get(),
            "appid"=>$appid,"uniq_id"=>$uniq_id,"type"=>$type
        ])->toArray();
    }



}