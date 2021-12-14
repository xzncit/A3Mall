<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\common\service\wechat;

use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersGroup as UsersGroupModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;

class WechatUser {

    public static function register($user){
        $user_id = 0;
        if($wechatUsers = WechatUsersModel::where(["openid"=>$user["openid"]])->find()){
            $user_id = $wechatUsers["user_id"];
        }

        if($user_id == 0 || !UsersModel::where(["id"=>$user_id])->count()){
            $group_id = UsersGroupModel::order('minexp','ASC')->value("id");
            $password = md5(time() . $user["openid"]);
            $data = [
                "group_id"=>$group_id,
                "username"=>'wx_'.uniqid(),
                "nickname"=>$user["nickname"],
                "mobile"=>"",
                "password"=>$password,
                "status"=>0,
                "create_ip"=>getIP(),
                "last_ip"=>getIP(),
                "create_time"=>time(),
                "last_login"=>time()
            ];

            $user_id = UsersModel::create($data)->id;
        }

        if (!empty($user['subscribe_time'])) {
            $user['subscribe_create_time'] = $user['subscribe_time'];
        }else{
            $user['subscribe_create_time'] = time();
        }

        if(isset($user['privilege'])) unset($user['privilege']);
        if(empty($wechatUsers)){
            WechatUsersModel::create(array_merge($user,[ 'subscribe' => '1','user_id'=>$user_id ]));
        }

        return $user_id;
    }

}